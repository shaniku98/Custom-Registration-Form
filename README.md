# Custom Registration Form
 
# Background Information:

For Registration page please visit below url: 

http://practice_project.docker.localhost:8000/example/registration/form

For View All registered User Data please visit below url:

http://practice_project.docker.localhost:8000/example/registration/form

# Requirements:

Create a custom drupal 8 module to achieve following functionality.
1) Create a user registration form with following fields Username, First Name, Last Name, Gender, City, Country, State. Save form data into database.

2) Implement following server site and client side validation on developed form
  i) Username must be unique. Check uniqueness during typing of username via ajax
  ii) Make all fields required.  (client side and server side)
  iii) country, state and city must be dependent on each other (Like if someone select India than states of India should be in state field and after state selection only respected cities should be in city drop down

3) Introduce custom table data to views (using custom module) and list down all records in tabular format

4) Custom Tables must be created during module installation.