This cron script will match trades existing in REON's database - if successful, it'll send an email to the users (as required by Pokémon Crystal) for them to check.

Configuring the program
------

Open `Program.cs` in a text editor/IDE, and edit the following strings:
- `EMAIL` should be the email that you're sending from (ex: 'pokecenter@sub1.server.tld')
- `PASS` should be the password for this email account.
- `SMTP` should be the SMTP server you're using (127.0.0.1 or the FQDN)
- `MYSQLUSER` and `MYSQLPW` should be your MySQL credentials that can read/write to the trades table in the database `MYSQLDB`

This script should be ran as a cronjob at a periodic length of time; we recommend an hour, which can be done by adding the following to your crontab:
`0 *  * * * /path/to/compiled-program > /dev/null`

TO-DO: Create a config file for it to use.

Credits
------

Written by [thomasnet](https://github.com/thomasnet-mc)
