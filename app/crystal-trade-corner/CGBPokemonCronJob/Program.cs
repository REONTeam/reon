using MailKit.Net.Smtp;
using MimeKit;
using MySql.Data.MySqlClient;
using System;
using System.Data;
using System.Threading.Tasks;
using System.Linq;
using System.Collections.Generic;

namespace CGBPokemonCronJob
{
    class Program
    {
        static async Task SendEmailAsync(string email,
            string trainerId, string secretId,
            int speciesOffered, string genderOffered,
            int speciesRequested, string genderRequested,
            string body)
        {
            var mimeMessage = new MimeMessage();
            mimeMessage.From.Add(new MailboxAddress("EMAIL"));
            mimeMessage.To.Add(new MailboxAddress(email));
            mimeMessage.Headers.Add("X-Game-code", "CGB-BXTJ-00");
            mimeMessage.Headers.Add("X-Game-result", $"1 {trainerId}{secretId} {genderOffered}{speciesOffered:x2} {genderRequested}{speciesRequested:x2}} 1");
            mimeMessage.Headers.Add("X-GBmail-type", "exclusive");

            mimeMessage.Body = new TextPart()
            {
                Text = body
            };

            var formatOptions = new FormatOptions();
            formatOptions.HiddenHeaders.Add(HeaderId.ContentType);
            formatOptions.HiddenHeaders.Add(HeaderId.MessageId);
            formatOptions.HiddenHeaders.Add(HeaderId.MimeVersion);
            formatOptions.HiddenHeaders.Add(HeaderId.Subject);

            using (var client = new SmtpClient())
            {
                await client.ConnectAsync("SMTP", 25, MailKit.Security.SecureSocketOptions.None);
                await client.AuthenticateAsync("EMAIL", "PASS");

                await client.SendAsync(formatOptions, mimeMessage);

                await client.DisconnectAsync(true);

                Console.WriteLine($"Sent email to {email} successfully.");
            }
        }

        static async Task DoJob()
        {
            const string connString = "server=127.0.0.1;userid=MYSQLUSER;password=MYSQLPW;database=MYSQLDB";

            MySqlConnection conn = null;

            try
            {
                conn = new MySqlConnection(connString);
                await conn.OpenAsync();

                const string query =
                  @"SELECT
                        t.*, t2.*
                    FROM pkm_trades AS t
                        INNER JOIN pkm_trades AS t2
                    WHERE
                        t.offer_species = t2.request_species
                        AND
                        (t.request_gender = '03'
                        OR
                        t.offer_gender = t2.request_gender)
                    HAVING COUNT(*) > 1;";
                MySqlDataAdapter da = new MySqlDataAdapter(query, conn);
                DataSet ds = new DataSet();
                await da.FillAsync(ds, "pkm_trades");
                DataTable dt = ds.Tables["pkm_trades"];

                var tasks = new List<Task>();

                foreach (DataRow row in dt.Rows)
                {
                    Console.Write("Found trade {0} corresponding to {1}.\n", 
                        row["tradeid"], row["tradeid1"]);
                    tasks.Add(SendEmailAsync((string)row["email"], (string)row["trainer_id"], (string)row["secret_id"],
                        (int)row["offer_species"], (string)row["offer_gender"],
                        (int)row["request_species"], (string)row["request_gender"],
                        (string)row["file1"]));
                    tasks.Add(SendEmailAsync((string)row["email1"], (string)row["trainer_id1"], (string)row["secret_id1"],
                        (int)row["offer_species1"], (string)row["offer_gender1"],
                        (int)row["request_species1"], (string)row["request_gender1"],
                        (string)row["file"]));
                    var delcmd = new MySqlCommand($"DELETE FROM pkm_trades WHERE tradeid = @tradeid OR tradeid = @tradeid1",
                        conn);
                    delcmd.Parameters.AddWithValue("@tradeid", row["tradeid"]);
                    delcmd.Parameters.AddWithValue("@tradeid1", row["tradeid1"]);
                    await delcmd.ExecuteNonQueryAsync();
                }
                await da.UpdateAsync(ds, "pkm_trades");

                await Task.WhenAll(tasks);
            } catch (Exception e)
            {
                Console.WriteLine("Error: {0}", e.ToString());
            }
            finally
            {
                if (conn != null)
                    conn.Close();
            }
        }

        static void Main(string[] args)
        {
            DoJob().Wait();
        }
    }
}
