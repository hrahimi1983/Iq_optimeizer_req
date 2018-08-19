# Iq_optimeizer_req

1 Rationale
 

To get a valid impression about the applicants technical skills we ask to develop a small website including the following objectives.

 

Please use the following technologies where applicable:

 

- symfony
- doctrine
- json/ajax
 


Our focus is not only about the working function but also on structure and readability of the code.

 

Please send all files required to install and run the system on a system with php>= 7.0 and mysql/mariadb accessible via localhost tcp port 3306. Do not put too much effort in the optical design of the frontend. In total it should not take more than 3 working days to complete.

 

During our second interview we would like to discuss your design desicions.

 

2 Objectives
The businessrequirement is a basic website that allows the documentation of changes to various application systems so that it would be easy to track errors in production in relation to changes made just before these errors startet to occur.

The frontends should include:

2.1 Application management
This frontend allows to add, edit and remove an application. 

 

The required fields are name of application and a timestamp of creation or change of the record.

 

2.2 Changelog
This frontend allows the selection of one of the defined applications. When an application has been selected, the frontend should load the latest change records (up to 10) in descending order by timestamp. 

 

It should be possible to open an existing record by click on the headline for editing. The edit view should have buttons to update and delete the active record. 

 

On top of the changelog there should be a text input for a title, a multiline input for a description of the changes and a save button to add new log entries. 

 

For user convenience, the changlog should be loaded and updated using json/ajax.


# installation:
1. clone the code
2. go to dir cd iq_optimeizer_req/
3. run "composer install" to install all dependencies 
4. set database config in .env 
      DATABASE_URL=mysql://root:@127.0.0.1:3306/app_manager

5.run bin/console doctrine:database:create to create database
6.run bin/console doctrine:migration:migrate
7.run bin/console server:run
8. go to your browser and address to :http://app.local:8000/



