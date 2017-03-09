Golden Bible

Site with charity links [Red Cross and others] for many countries.

To deploy site you need to create two databases: 
`gbible` - main base of site, 
`sofia` - base of scriptures.

In ./install/ directory you can find scripts for setting up database.

php directory must be set in PATH variable [for Windows right click on your computer [or Windows + Pause/Break] -> Properties,.. and somewhere on tabs there "Environment Variables" -> Variables of System -> PATH -> Edit].

Download all *_vpl.zip files from http://ebible.org/Scriptures/ [you can use Internet Download Accelerator - Download Master], all archives must be unpacked in its own directory `./*_vpl/`. All scripts must be in directory with these VPL.

1. To run it use this command: 
php -f prepare.php
2. Then in PHPMyAdmin or other database view run script countries.sql.
3. Site shall be available at your address [http://127.0.0.1/ for local machine],
if it doesn't show Bible texts - run directly update commands for Bible database:
php -f just_prepare_sql.php