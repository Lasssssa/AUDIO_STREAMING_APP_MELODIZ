# Mélodiz Projet CIR2 - Fin d'année

Creator of **Mélodiz** : Théo Porodo / Paul Brochoire / Matthieu Stéphant

# Testing the current VM : 10.10.51.74
The original version of Mélodiz is currently available at 10.10.51.74 (ISEN network). 
### Web application test section 
You can test the site at 10.10.51.74
- Test account 1: Email : theo@gmail.com | Password :  mdp
- Test account 2: Email : vincent.rocher@gmail.com | Password : mdp

### SSH VM connection part
- To connect to the virtual machine : 
- Use the command `ssh user1@10.10.51.74` in a linux console
- User 1's password is: `mdpGroup`.
- You can then access the application's source code in the following folder: `cd /var/www/html/projet_CIR2_web/`

# Setup the linux machine

### Update
- `sudo apt-get update`
- `sudo apt-get upgrade`

### Install apache2 Version : 2.4.56
- `sudo apt-get install apache2`

### Install postgresql Version : 13.11
- `sudo apt-get install postgresql`
- `sudo nano /etc/postgresql/13/main/pg_hba.conf`

Change `peer` to `trust` on the lines `local all postgres` and `local all all`

### Install PHP 7.4.33 and module pgsql
- `sudo apt-get update`
- `sudo apt-get install php`
- `sudo apt-get install php-pgsql`

- `sudo service apache2 restart`
- `sudo service postgresql restart`

# Clone the repository Github in apache
### Install git
- `apt-get install git git-core`
### Clone the repository
- `cd /var/www/html`
- `sudo git clone https://github.com/Lasssssa/projet_CIR2_web/` 
This may take some time, as some of our music is stored on the server (in its entirety).
### Update
- `cd /var/www/html/projet_CIR2_web`
- `sudo git pull https://github.com/Lasssssa/projet_CIR2_web/`

# Setup the apache2 configuration

### To setup the server configuration :
- Move to the directory : `cd /var/www/html/projet_CIR2_web`
- Use the command : `cp 000-default.conf /etc/apache2/sites-enabled`
- Restart apache2 : `sudo service apache2 restart`
#### After that, don't forget to delete 000-default.conf from the repetory because that is not very safe to keep it here

# Create the database

### Connect as super user
- `psql postgres postgres`

### Create the database with related role
- `CREATE ROLE adminprojet LOGIN ENCRYPTED PASSWORD 'mdpProjet';`
- `CREATE DATABASE musiquedb WITH OWNER adminprojet;`
- Leave the database using `\q`

### Create the table and fill it
To create the table, use the file `model.sql` which is in the dir `sql`.
To fill the database, use the file `data.sql` which is in the dir `sql` 

First move to that dir
- `cd /var/www/html/projet_CIR2_web`

Then execute the file using : 
- `psql -d musiquedb -U adminprojet -f model.sql`
To fill the database : 
- `psql -d musiquedb -U adminprojet -f data.sql`

Now your server is all setup and you can start the application

# Use of the site

### Create account and start using Mélodiz

- You can create an account if you want to start from 0

### Connect as test account

- Once you've filled in the tables, you've created 2 test users, who you can connect to in order to test the connection functionality.
- You can connect to it, here are the informations : 
- Account 1 : theo@gmail.com --> mdp
- Account 2 : vincent.rocher@gmail.com --> mdp
