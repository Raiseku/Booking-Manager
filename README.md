# **Pizza Booking Management Web Service**

This project aimed to create a web service capable of managing reservations for a series of pizzerias. The service was developed using AngularJS for REST services and PHP for database queries. The MySQL database was created using Xampp, and data was exchanged between PHP and AngularJS in JSON format. 

## **Features**

- User registration and login
- Customer can view, modify and delete their bookings
- Customer can create new bookings
- Customer can delete their account
- Pizzeria registration and login
- Pizzeria can view, modify and delete their availability for bookings
- Pizzeria can delete their account

## **Implementation**

The web service has been developed using the following technologies:

- XAMPP: used for server management
- AngularJS: used to create REST services
- MySQL: used for database management
- PHP: used to handle database queries
- JSON: data is exchanged in JSON format between PHP and AngularJS
- HTML: used for web page development

The implementation files have been structured in the following way:

- index.html: this file is loaded by AngularJS and displays all the implemented templates for the project
- css folder: contains the bootstrap.css file downloaded from **[https://bootswatch.com/](https://bootswatch.com/)**, which facilitated the implementation and styling of the HTML pages
- js folder: contains all the files for the use of AngularJS, and the app.js file contains all the necessary methods for the service to function
- Templates folder: contains all the HTML files for displaying the site, which are called within index.html using the AngularJS tag <ng-view>
- webservices folder: contains all the PHP files used to query the database. Specifically, the config.php file specifies how to avoid the CORS policy that prevents data exchange between pages. This policy has been disabled to allow the REST service to function correctly.

## **Database Structure**

The database has been created with three fixed tables:

- Clients table: stores all client data at the time of registration

```sql
CREATE TABLE clients(
    email VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255),
    name VARCHAR(255),
    surname VARCHAR(255),
    address VARCHAR(255)
)
```

- Pizzerias list table: stores all pizzeria data at the time of registration

```sql
CREATE TABLE pizzerias_list(
    pizzeria_name VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255)
);
```

- Bookings table: stores all bookings made by customers

```sql
CREATE TABLE bookings(
    id INT PRIMARY KEY AUTO_INCREMENT,
    pizzeria VARCHAR(255) REFERENCES pizzerias_list(pizzeria_name),
    customer_email VARCHAR(255) REFERENCES clients(email),
    date DATE,
    number_of_seats INT NOT NULL,
    hour INT NOT NULL,
    minute INT NOT NULL
);
```

Additionally, a table is implemented for each registered pizzeria, which is used to manage the availability for different days:

```sql
CREATE TABLE pizzeria_name(
    date DATE,
    available_seats INT,
    total_seats INT,
    opening_hour INT,
    closing_hour INT,
    number_of_tables INT,
    PRIMARY KEY (date)
);
```

## **REST Service Construction**

The REST service within the site has been implemented using AngularJS. The site is managed by controllers defined in the app.js file and called in the different HTML files. Through these controllers, it is possible to call the relevant PHP file and pass parameters using 'POST' and 'GET'. Additionally, some AngularJS functionality (such as ng-repeat) has been used to display data obtained from the database on the HTML page.
