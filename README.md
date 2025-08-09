## InquiryX Dashboard - Documentation

Overview
InquiryX Dashboard is a Business Inquiry Management Dashboard built with PHP, MySQL, Bootstrap 5, and Chart.js. It helps track inquiries, follow-ups, and customer details in an easy-to-use web interface.
Features
- Total, Ongoing, Closed & Today’s Inquiries
- Inquiry Status Distribution (Pie Chart)
- Monthly Inquiry Trends (Bar Chart)
- Active Inquiry Table with Customer Details
- Responsive Full-Screen Dashboard
Tech Stack
• PHP 8+
• MySQL
• Bootstrap 5
• Chart.js
Project Structure

InquiryX-Dashboard/

├── includes/

│   └── db.php          # Database connection

├── dashboard.php       # Main dashboard

├── add_customer.php    # Add new customers

├── add_inquiry.php     # Add inquiries

├── followup.php        # Manage followups

├── view_inquiries.php  # View all inquiries

└── inquiry_dashboard.sql  # Database schema

Installation
1️⃣ Clone the repo:
      
   git clone https://github.com/yourusername/InquiryX-Dashboard.git

2️⃣ Import the database:

- Open phpMyAdmin

- Create database `inquiry_dashboard`

- Import `inquiry_dashboard.sql`

3️⃣ Configure DB credentials in `includes/db.php`:

$conn = new mysqli('localhost', 'root', '', 'inquiry_dashboard');

4️⃣ Run in browser:
   http://localhost/InquiryX-Dashboard/dashboard.php

# Screenshots

<img width="1918" height="871" alt="image" src="https://github.com/user-attachments/assets/647190aa-6068-447b-96b6-db3bfd0dae43" />
<img width="1919" height="869" alt="image" src="https://github.com/user-attachments/assets/741a5fb7-febd-45c1-8ff4-07b2f2aaa8ab" />
<img width="1919" height="865" alt="image" src="https://github.com/user-attachments/assets/4deafa84-36bd-44a3-8014-a5342b8ee5e2" />
<img width="1919" height="843" alt="image" src="https://github.com/user-attachments/assets/3e8c6618-22dc-482b-b6b3-7c3b925ae85f" />
<img width="1913" height="858" alt="image" src="https://github.com/user-attachments/assets/42c260f9-d1e0-404f-bc62-c190c1d25b03" />
<img width="1919" height="860" alt="image" src="https://github.com/user-attachments/assets/a538a8d5-b009-4a8c-9cff-9830e0ac28b4" />

License
MIT License – Free to use & modify.
