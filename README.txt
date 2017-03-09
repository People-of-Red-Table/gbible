Golden Bible [http://gold-bible.com/]. Project of Charity Support Community PoRT [http://charity-port.16mb.com/].

Site with charity links [Red Cross and others] for many countries.

To deploy site you need to create two databases: 
`gbible` - main base of site, 
`sofia` - base of scriptures.

In ./install/ directory you can find scripts for setting up database.

php directory must be set in PATH variable [for Windows right click on your computer [or Windows + Pause/Break] -> Properties,.. and somewhere on tabs there "Environment Variables" -> Variables of System -> PATH -> Edit].

Download all *_vpl.zip files from http://ebible.org/Scriptures/. File `urls.txt` contains links for these VPL. You can use Internet Download Accelerator - Download Master. All archives must be unpacked in its own directory `./*_vpl/`. All scripts must be in directory with these VPL.

1. To run it use this command: 
php -f prepare.php
If during these script you've got a "stop" at a VPL, possibly it's because there is no SQL file. Run `php -f make_sql.php %translation_name_vpl%`. This script will make `*_vpl.sql` file.
To continue on specific VPL type `php -f prepare.php name_vpl` and script will skip all previous translations and will start from `name_vpl`.
2. Then in PHPMyAdmin or other database view run script countries.sql.
3. Site shall be available at your address [http://127.0.0.1/ for local machine],
if it doesn't show Bible texts - run directly update commands for Bible database:
php -f just_prepare_sql.php