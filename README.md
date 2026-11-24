# Obstmarkt Online-Shop | Fruit E-Commerce Web App

*[English version below](#-english-version)*

---

## 🇩🇪 Deutsch

### Projektbeschreibung
Ein vollständiger, datenbankgestützter E-Commerce-Onlineshop für frische Obstwaren, entwickelt mit **PHP**, **MySQL**, **HTML5**, **CSS3** und **Vanilla JavaScript (Fetch API)**. Das Projekt bildet den gesamten Kaufprozess ab – von der Produktübersicht über Registrierung und Anmeldung bis hin zum dynamischen Warenkorb, dem Checkout sowie einem geschützten Admin-Dashboard zur Verwaltung von Benutzern, Artikeln, Kategorien und Lieferanten.

### Hauptfunktionen
* **Produktkatalog & Vorschau (`startseite.php`, `Index.php`):** Dynamisches Laden der Artikel inklusive Preis pro Kilogramm, Beschreibung und Produktbildern aus der MySQL-Datenbank.
* **Asynchroner Warenkorb (`bestellung.php`, `warenkorb.php`):** Hinzufügen von Artikeln per JavaScript `fetch()` ohne Seitenreload, automatische Mengenaktualisierung bei bereits vorhandenen Tagesartikeln sowie Erhöhen, Verringern und Entfernen von Positionen.
* **Checkout & Bestellbestätigung (`daten.php`, `kasse.php`):** Automatische Berechnung von Zwischensummen und Gesamtbetrag sowie Bestellübersicht für angemeldete Kunden.
* **Benutzer- & Rollenverwaltung (`login.php`, `register.php`):** Registrierung mit Passwort-Hashing (`password_hash` / `password_verify`), Adressverwaltung und rollenbasierte Weiterleitung (`user` vs. `admin`) über einen datenbankbasierten Anmeldestatus.
* **Admin-Dashboard (`admin.php`):** Zentrale Verwaltungsoberfläche zum Erstellen, Bearbeiten und Löschen von Benutzern, Artikeln, Kategorien und Lieferanten sowie Copy-to-Clipboard-Hilfsfunktionen für IDs.

### Verwendete Technologien
* **Backend:** PHP 8.x (MySQLi)
* **Datenbank:** MySQL / MariaDB (`mydb.sql`)
* **Frontend:** HTML5, CSS3 (Flexbox, Responsive Media Queries, Google Material Symbols), JavaScript (ES6, Fetch API)

### Projektstruktur
```text
├── Index.php          # Hauptshop für angemeldete Kunden
├── startseite.php     # Öffentliche Produktvorschau & Einstiegsseite
├── login.php          # Anmeldung für Kunden und Administratoren
├── bestellung.php     # Backend-Endpunkt für das Hinzufügen in den Warenkorb
├── warenkorb.php      # Warenkorb-Ansicht & Mengenverwaltung
├── daten.php          # Kassen-Vorstufe (Anmelden / Registrieren)
├── kasse.php          # Bestellbestätigung & Gesamtsummenberechnung
├── css.css            # Zentrales Stylesheet & Responsive Design
├── mydb.sql           # Datenbankschema & Beispieldaten
└── user/
    ├── register.php   # Kundenregistrierung
    └── admin.php      # Admin-Bereich (CRUD für User, Artikel, Kategorien, Lieferanten) (admin@gmail.com, admin) (test@gmail.com, test)
```
---

## 🇬🇧 English Version

# Obstmarkt Online-Shop | Fruit E-Commerce Web App

A full-stack, database-driven e-commerce web application for fresh fruit products built with **PHP**, **MySQL**, **HTML5**, **CSS3**, and **Vanilla JavaScript (Fetch API)**. The application covers the complete shopping workflow—from product browsing, user registration, and authentication to a dynamic shopping cart, checkout summary, and a protected admin dashboard for managing users, products, categories, and suppliers.

---

## Key Features

* **Product Catalog & Storefront (`startseite.php`, `Index.php`):** Dynamically renders products including price per kilogram, descriptions, and product images directly from the MySQL database.
* **Asynchronous Shopping Cart (`bestellung.php`, `warenkorb.php`):** Adds items via JavaScript `fetch()` without full page reloads, automatically increments quantities for duplicate daily items, and supports incrementing, decrementing, and removing cart entries.
* **Checkout & Order Confirmation (`daten.php`, `kasse.php`):** Automatically calculates item subtotals and order totals with a confirmation view for logged-in users.
* **Authentication & Role Management (`login.php`, `register.php`):** User registration with secure password hashing (`password_hash` / `password_verify`), address storage, and role-based routing (`user` vs. `admin`) backed by database status tracking.
* **Admin Dashboard (`admin.php`):** Centralized CRUD interface to create, update, and delete users, products, categories, and suppliers, featuring one-click ID copy functionality.

---

## Tech Stack

* **Backend:** PHP 8.x (MySQLi)
* **Database:** MySQL / MariaDB (`mydb.sql`)
* **Frontend:** HTML5, CSS3 (Flexbox, Responsive Media Queries, Google Material Symbols), JavaScript (ES6, Fetch API)

---

## Project Structure

```text
├── Index.php          # Main storefront for logged-in customers
├── startseite.php     # Public product preview & landing page
├── login.php          # Authentication for customers and administrators
├── bestellung.php     # Backend endpoint for adding items to the shopping cart
├── warenkorb.php      # Shopping cart view & quantity management
├── daten.php          # Pre-checkout step (Login / Register prompt)
├── kasse.php          # Order confirmation & total calculation
├── css.css            # Global stylesheet & responsive layout
├── mydb.sql           # Database schema & sample seed data
└── user/
    ├── register.php   # Customer registration form
    └── admin.php      # Admin panel (CRUD for users, products, categories, suppliers)
