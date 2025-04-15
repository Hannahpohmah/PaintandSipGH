
# PaintSipGH

PaintSipGH is a creative art studio that offers immersive painting experiences where participants can create beautiful artworks while enjoying a relaxing social atmosphere. Our platform allows users to book painting sessions, join themed events, and explore their artistic talents with guided sessions. The goal is to foster creativity and community connection through art.

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [System Design and Analysis](#System Design Analysis)
  - [Requirement gathering](#prerequisites)
  - [functional Requirement](#installation)
  - [Non functional Requirement](#usage)
  - [High Level Architecture](#Architecture)
  - [Entity relation diagram](#ER)
  - [Activity diagram user](#AD1)
  - [Activity diagram patner](#AD2)
- [Technologies Used](#technologies-used)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Usage](#usage)
- [Booking a Session](#booking-a-session)
- [Project Structure](#project-structure)
- [License](#license)
- [Contact](#contact)

## Project Overview

PaintSipGH provides an online platform for users to easily book painting sessions, view upcoming events, and access instructional materials for creating art. Whether you're a beginner or an experienced artist, our platform offers a guided experience with expert instructors in a fun and relaxed environment.

### Key Features:
- **Session Booking**: Users can select painting sessions from different themes and dates.
- **Interactive Events**: Join fun, themed events to meet new people and create unique artwork.
- **Easy Payment Integration**: Secure online payments for easy booking.
- **User Profiles**: Participants can manage their bookings, track past sessions

### System Design Analysis

**Requirements Gathering**
- As a customer, I want to securely log in to my account and also securely make payments so that my personal information and order history are protected.[Security]
- As a customer, I want to search for products by themes, location and dates so that I can easily find what I am looking for.[Product Search]
- As a customer, I want to view the contents of my shopping cart, including the products, quantity, and total price, so I can review my selection, adjust the quantity of products, before proceeding to checkout.[cart management]
- As an admin, I want to manage the product catalog by adding new products, updating existing ones with accurate details, and removing out-of-stock or discontinued products to ensure customers can view and purchase only available items.[Inventory mananagement]
- As a customer, I want to choose from multiple payment methods (credit card, debit card, mobile wallet) to complete my purchase, as wells as recieve my invoice tickets via a preferred method.[payment processing]
- As an admin, I want to see sales analytics, including , total revenue, and upcoming events, so I can make data-driven decisions.

**Functional Requirements**

1- Security:

Authentication: The platform uses secure authentication mechanisms with user passwords being hashed before storage in the database.
Data Encryption: Sensitive customer data such as payment details is encrypted using HTTPS for secure transmission. PayStack API for secure payment processing, ensuring that sensitive financial data is handled safely.

2- Inventory/Product/Service Catalog Management:

The platform features an easy-to-manage catalog for painting sessions and events. Administrators can add, update, and remove painting sessions directly from the admin interface. Each session has detailed information like date, theme, and price.
The inventory management system supports easy updates and provides an interface for admin users to modify session availability.

3- Product/Service Search and Filtering:

Users can filter painting sessions based on theme, date, and price. This allows them to search for available sessions based on their preferences and schedule.

4- Product Cart / Service Booking Management:

Users can add painting sessions to their cart, adjust quantities (number of participants), and remove sessions from their cart.
The cart system is persistent across sessions, meaning that even if the user logs out and returns later, their cart items are retained.

5- Customer Order Management:

The platform tracks customer orders through each stage of the booking process: from adding the session to the cart, proceeding to checkout, making payment, and receiving confirmation.
Order details are stored in the database, including customer information, payment status, and session details.


6- Payment Processing:

Paystack API is integrated to handle various payment methods including credit cards, debit cards, and mobile wallets.

7- Invoicing System Management:

Invoices are automatically generated for completed orders. Customers receive invoices via email upon confirmation of payment.
The system allows users to view past invoices in their profile and download them for future reference. Payment statuses are also tracked, ensuring smooth management of order and payment records.

- **Non-Functional Requirements**
1- Hosting & Scalability:

The platform is hosted on a reliable provider like the Ashesi public server, ensuring high availability, scalability, and fast response times.
The app is designed to scale, allowing more painting sessions to be added and user traffic to be handled effectively as the platform grows.

2- User-Friendliness:

The platform is designed to be intuitive and easy to use. The user interface is simple and clean, with clear navigation and accessible features for both booking sessions and managing orders.
Mobile responsiveness is prioritized, ensuring the platform is fully functional on smartphones, tablets, and desktops.

3- Compliance with Legal and Regulatory Requirements:

The platform adheres  to relevant legal and regulatory requirements, particularly concerning data privacy and payment processing.

Data Privacy: The platform complies with international standards such as GDPR for user data protection. All personal information is stored securely, and users have control over their data, including the ability to request deletion of their data.
Payment Compliance: Payments are processed through Stripe, which is PCI-DSS compliant, ensuring that all financial transactions meet the required security standards.

## Technologies Used

- **Frontend**:
  - HTML5
  - CSS3 (with Flexbox and Grid for layout)
  - Php
  
- **Backend**:
  - PHP
  - MySQL (for database management)
  - Paystack API (for payment processing)
  
- **Hosting**: 
  - Web Hosting (e.g., Hostinger, DigitalOcean)
  
- **Other Tools**:
  - Git (version control)
  - VS Code (development environment)
  - Xampp
  
## Getting Started

### Prerequisites

Before you begin, ensure that you have the following installed on your local machine:
- PHP
- MySQL
- A local server environment such as **XAMPP** or **MAMP** (for local development)
- A code editor such as **VS Code**

### Installation

1. Download the file PAintSipGH, it contains all source files 

2. Import the file into the htdocs of your Xampp folder

3. Set up the database:
   - Create a new MySQL database (e.g., `paintsipgh_db`).
   - Import the database schema (located in the `database` folder) using PHPMyAdmin or MySQL commands.
   - Update the database connection details in the `config.php` file.

4. Install dependencies:
   - If you are using external libraries or APIs, make sure to include them via `composer` or directly linking to the appropriate sources in the HTML files.

5. Start your local server (XAMPP, MAMP, etc.) and navigate to the directory where the project files are stored.

### Usage

1. Open your browser and go to  http://169.239.251.102:4442/~hannah.nah-anzoh (or the local URL provided by your server setup).
2. Explore the homepage, browse upcoming painting sessions, and follow the steps to book a session.
3. For admin access, login using the credentials provided in the [event organizer username: partnerA@example.com, password:hannah]. for normal users you can either register or login with this user: username:cherish@gmail.com password:hh
4. Use the "Booking" section to reserve a spot for any upcoming event, and make secure payments via the integrated Stripe API.

## Booking a Session

Booking a session is easy! Follow these steps:

1. Navigate to the **"Book a Session"** page.
2. Browse through the available painting themes and select your preferred session date.
3. Fill in the required details (name, email, phone number).
4. Choose your payment method and complete the transaction via Stripe.
5. Once the payment is confirmed, you will receive a confirmation email with your booking details.

## Project Structure

Here's a breakdown of the project directory:
DEMOE: https://youtu.be/jQDAmL7CNU4

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

If you have any questions or would like to get in touch, feel free to reach out to us:

- Email: [info@paintsipgh.com](mailto:info@paintsipgh.com)
- Instagram: [@paintsipgh](https://www.instagram.com/paintsipgh)

---

Thank you for exploring the PaintSipGH project. We hope you enjoy your creative journey with us!
