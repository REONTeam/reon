# trade-corner

Cron script to automatically perform trades already in database.

## Setup

Open `Program.cs` in a text editor, and edit the following strings:
- `EMAIL` should be the email that you're sending from (ex: 'pokecenter@sub1.server.tld')
- `PASS` should be the password for this email account.
- `SMTP` should be the SMTP server you're using (127.0.0.1 or the FQDN)
- `MYSQLUSER` and `MYSQLPW` should be your MySQL credentials that can read/write to the trades table in the database `MYSQLDB`

This script should be ran as a cronjob or other automatically ran script, as it will perform the trades.

TO-DO: Create a config file for it to use.

## Credits

Written by [thomasnet](https://github.com/thomasnet-mc)
