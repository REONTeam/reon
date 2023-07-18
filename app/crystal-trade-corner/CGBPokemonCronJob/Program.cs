// SPDX-License-Identifier: MIT

using MailKit.Net.Smtp;
using MimeKit;
using MySql.Data.MySqlClient;
using System;
using System.Text;
using System.Text.Json;
using System.IO;
using System.Data;
using System.Threading.Tasks;
using System.Linq;
using System.Collections.Generic;

namespace CGBPokemonCronJob
{

    static class sqldata
    {
        public static MySqlConnection conn = null;

        public static async void Connect(string filename)
        {
            string config = File.ReadAllText(filename);
            using JsonDocument doc = JsonDocument.Parse(config);
            JsonElement root = doc.RootElement;
            string connString = "server=" + root.GetProperty("mysql_host") + ";userid=" + root.GetProperty("mysql_user") + ";password=" + root.GetProperty("mysql_password") + ";database=" + root.GetProperty("mysql_database");
            conn = new MySqlConnection(connString);
            await conn.OpenAsync();
        }
    }

    class Program
    {
        static async Task SendEmailAsync(string email,
            string trainerId, string secretId,
            int speciesOffered, string genderOffered,
            int speciesRequested, string genderRequested,
            string body, string game)
        {
            string fromAddress = "From: MISSINGNO.\r\n";
            string gameCode = "X-Game-code: CGB-" + game.ToUpper() + "-00\r\n";
            string gameResult = "X-Game-result: " + $"1 {trainerId}{secretId} {genderOffered}{speciesOffered:x2} {genderRequested}{speciesRequested:x2} 1\r\n";
            string gbMailType = "X-GBmail-type: exclusive\r\n";

            string emailFull = fromAddress + gameCode + gameResult + gbMailType + "\r\n" + body + "\r\n";
            byte[] utf8Email = Encoding.ASCII.GetBytes(emailFull);

            MemoryStream eml = new MemoryStream();
            await eml.WriteAsync(utf8Email);
            var eml_blob = eml.ToArray();

            var mail_query = new MySqlCommand($"INSERT INTO mail (sender,recipient,content) VALUES(@fromAddress,@email,@eml)",sqldata.conn); 
            mail_query.Parameters.AddWithValue("@fromAddress", fromAddress);
            mail_query.Parameters.AddWithValue("@email", email.Substring(0, email.IndexOf("@")));
            mail_query.Parameters.AddWithValue("@eml", eml_blob);

            await mail_query.ExecuteNonQueryAsync();
            Console.WriteLine($"Sent email to {email} successfully.");

        }

        static async Task DoJob(string game, string config)
        {
            try
            {
                sqldata.Connect(config);

                string query =
                  @$"SELECT
                        t.*, t2.*
                    FROM {game}_pkm_trades AS t
                        INNER JOIN {game}_pkm_trades AS t2
                    WHERE
                        t.offer_species = t2.request_species
                        AND
                        ((t.request_gender = '03'
			OR
			t.request_gender = '00')
                        OR
                        t.offer_gender = t2.request_gender)
                    HAVING COUNT(*) > 1;";
                MySqlDataAdapter da = new MySqlDataAdapter(query, sqldata.conn);
                DataSet ds = new DataSet();
                await da.FillAsync(ds, $"{game}_pkm_trades");
                DataTable dt = ds.Tables[$"{game}_pkm_trades"];

                var tasks = new List<Task>();

                foreach (DataRow row in dt.Rows)
                {
                    Console.Write("Found trade {0} corresponding to {1}.\n", 
                        row["tradeid"], row["tradeid1"]);
                    tasks.Add(SendEmailAsync((string)row["email"], (string)row["trainer_id"], (string)row["secret_id"],
                        (int)row["offer_species"], (string)row["offer_gender"],
                        (int)row["request_species"], (string)row["request_gender"],
                        (string)row["file1"], game));
                    tasks.Add(SendEmailAsync((string)row["email1"], (string)row["trainer_id1"], (string)row["secret_id1"],
                        (int)row["offer_species1"], (string)row["offer_gender1"],
                        (int)row["request_species1"], (string)row["request_gender1"],
                        (string)row["file"], game));
                    var delcmd = new MySqlCommand($"DELETE FROM {game}_pkm_trades WHERE tradeid = @tradeid OR tradeid = @tradeid1",
                        sqldata.conn);
                    delcmd.Parameters.AddWithValue("@tradeid", row["tradeid"]);
                    delcmd.Parameters.AddWithValue("@tradeid1", row["tradeid1"]);
                    await delcmd.ExecuteNonQueryAsync();
                }
                await da.UpdateAsync(ds, $"{game}_pkm_trades");

                await Task.WhenAll(tasks);
            } catch (Exception e)
            {
                Console.WriteLine("Error: {0}", e.ToString());
            }
            finally
            {
                if (sqldata.conn != null)
                    sqldata.conn.Close();
            }
        }

        static void Main(string[] args)
        {
            if(args.Length != 2)
            {
                Console.WriteLine("CGBPokemonCronJob.dll <gameid> <json config file>\n");
                System.Environment.Exit(0);
            }
            else DoJob(args[0].ToLower(), args[1]).Wait();
        }
    }
}
