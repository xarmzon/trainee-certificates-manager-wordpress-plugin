# Trainee Certificates Manager

A lightweight WordPress plugin for managing trainee records and issuing verifiable certificates with downloadable PDF support.

The plugin allows administrators to register trainees, assign one or more courses, generate unique certificate numbers, and verify certificates from a public page. Certificates can also be downloaded as printable PDFs.

---

# Features

- Trainee management using a custom post type
- Certificate verification page for public users
- Automatic QR code generation for certificate verification
- Downloadable PDF certificates
- Bulk trainee import via CSV
- Searchable trainee list in the WordPress admin
- Elementor-compatible verification page
- Multiple courses per trainee
- Certificate number based verification

---

# Plugin Folder Structure

trainee-certificates-manager
│
├── trainee-certificates-manager.php
│
├── core
│ ├── ajax-verify.php
│ └── pdf-generator.php
│
├── certificates
│ └── certificate-template.php
│
├── shortcodes
│ └── shortcode.php
│
├── utils
│ └── qr-generator.php
│
├── assets
│ ├── js
│ │ └── verify.js
│ └── css
│ └── style.css
│
└── vendor
└── dompdf

---

# Installation

### Install Plugin

1. Upload the plugin folder to: /wp-content/plugins/
2. Activate the plugin from the WordPress dashboard.

# Admin Usage

Navigate to: Dashboard → Trainees

You can:

- Add a new trainee
- Enter trainee full name
- Add courses
- Enter certificate number
