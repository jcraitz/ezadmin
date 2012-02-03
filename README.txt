To install the EZadmin application, you will need:

 1. A place to host web-accessible PHP code
 2. A MySQL database
 
 PHP code
 
 For the application to run, it must be deployed to a folder on a web-accessible server that can run PHP. EZadmin does not currently have
 authentication built into the application, so it is recommended that this folder be restricted to appropriate staff users using
 Apache configuration. 
 
 Please note that even when this is done, the database connection information in /application/config/config.xml could 
 be accessible via http to hackers, and you may want to consider:
 
 1) moving the whole application folder somewhere outside of web server's document root (recommended)
 OR 2) allowing no web access to the application folder (via apache configuration)
 
 See the instructions in /sample_localize.php in order to specify where you have installed the application files if you have 
 followed our advice in 1) above and moved the /application/ folder outside the web server's document root.
 
 To customize the links in the footer of the application, change the values in /application/config/config.xml.
 
 
 MySQL database
 
 A MySQL database should be created with an account that allows localhost access. The database name (schema name) does not matter. To create 
 the tables necessary for EZadmin, you can run the /application/sql/schema.sql SQL script. Please not that you will need to put your database 
 name at the top of that file (replace '<dbname>') for it to work properly. A database with that name (schema name) must already exist.
 
 Then you should also run the /application/sql/1.0.1_upgrade.sql SQL script.
 
 You will need to configure your database connection information in /application/config/config.xml.
 
 If you would like to populate the database with a little sample data, you can load the /application/sql/test_data.sql script.
 
EZProxy Configuration Files:
 
 EZadmin's purpose is to store EZProxy configuration information in a database and then write it out to configuration files that can
 be read by EZProxy. The location where these files should be written should be configured in /application/config/config.xml. 
 In order for these configuration files to affect EZProxy, they must be stored somewhere accessible to EZProxy, and 
 then EZProxy must be restarted to read in any updates to the configuration.