# NutriTrack - Nutrition Tracking Web Application

A modern web application for tracking your daily nutrition intake, built with PHP and Supabase.

## Features

✨ **User Authentication**
- Secure sign up and sign in
- Session management
- User profile management

🍽️ **Food Management**
- Add food items with nutritional information (calories, protein, carbs, fat)
- View your food history
- Delete food items
- User-specific food tracking

🎨 **Modern UI**
- Beautiful, responsive design
- Dark mode support
- Smooth animations and transitions

## Tech Stack

- **Frontend:** HTML, TailwindCSS, JavaScript
- **Backend:** PHP 8+
- **Database:** Supabase (PostgreSQL)
- **Server:** Apache (XAMPP)

## Prerequisites

- PHP 8.0 or higher
- XAMPP or similar Apache server
- Supabase account (free tier works!)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/nutritrack.git
cd nutritrack
```

### 2. Set Up Environment Variables

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` and add your Supabase credentials:

```env
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_KEY=your_supabase_anon_key_here
```

**Where to find these:**
1. Go to [Supabase Dashboard](https://app.supabase.com)
2. Select your project
3. Go to Settings → API
4. Copy the "Project URL" and "anon/public" key

### 3. Set Up Database Tables

Run these SQL commands in your Supabase SQL Editor:

```sql
-- Create user table
CREATE TABLE IF NOT EXISTS "user" (
    id BIGSERIAL PRIMARY KEY,
    username VARCHAR UNIQUE NOT NULL,
    password VARCHAR NOT NULL,
    fullname TEXT NOT NULL,
    email VARCHAR UNIQUE NOT NULL,
    phone VARCHAR,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Create food table
CREATE TABLE IF NOT EXISTS food (
    id BIGSERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    calories TEXT,
    protein TEXT,
    carbs TEXT,
    fat TEXT,
    username VARCHAR NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (username) REFERENCES "user"(username) ON DELETE CASCADE
);

-- Disable RLS for development (enable in production with proper policies)
ALTER TABLE "user" DISABLE ROW LEVEL SECURITY;
ALTER TABLE food DISABLE ROW LEVEL SECURITY;
```

### 4. Start XAMPP

1. Open XAMPP Control Panel
2. Start Apache
3. Place the project in `C:\xampp\htdocs\` (Windows) or `/Applications/XAMPP/htdocs/` (Mac)

### 5. Access the Application

Open your browser and go to:
```
http://localhost/nutritrack/src/
```

## Project Structure

```
nutritrack/
├── src/
│   ├── config.php           # Database configuration (uses .env)
│   ├── signin.php           # Login page
│   ├── signup.php           # Registration page
│   ├── dashboard.php        # Main dashboard
│   ├── food.php             # Food management
│   ├── logout.php           # Logout handler
│   └── output.css           # Compiled Tailwind CSS
├── .env                     # Environment variables (NOT in git)
├── .env.example             # Example environment file (in git)
├── .gitignore               # Git ignore file
└── README.md                # This file
```

## Security Notes

⚠️ **IMPORTANT:** Never commit the following to GitHub:
- `.env` file (contains your API keys)
- `config.php` if it has hardcoded credentials

✅ These are already in `.gitignore` to protect you!

## Production Deployment

Before deploying to production:

1. **Enable Row Level Security (RLS):**
   ```sql
   ALTER TABLE "user" ENABLE ROW LEVEL SECURITY;
   ALTER TABLE food ENABLE ROW LEVEL SECURITY;
   
   -- Add appropriate policies for your use case
   ```

2. **Use hashed passwords:**
   - Update signup.php to use `password_hash()`
   - Update signin.php to use `password_verify()`

3. **Use HTTPS:**
   - Ensure your domain has SSL certificate
   - Never use HTTP in production

4. **Set proper file permissions:**
   - `.env` should be readable only by the web server
   - Remove test/debug files

## Development

### Adding New Features

1. Create a new branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes

3. Test thoroughly

4. Commit and push:
   ```bash
   git add .
   git commit -m "Add your feature description"
   git push origin feature/your-feature-name
   ```

5. Create a Pull Request

### Database Schema Changes

If you modify the database schema:
1. Update the SQL in this README
2. Document the changes in commit message
3. Consider creating a migration file

## Troubleshooting

### "Failed to add item" Error
- Check that RLS is disabled for testing
- Verify your `.env` file has correct credentials
- Check that `id` column has auto-increment enabled

### "Username already taken" Error
- This is expected if the username exists
- Try a different username

### Can't see food items
- Make sure you're logged in
- Check that `username` column exists in `food` table
- Verify food items belong to your username

### Delete not working
- Check that the food item belongs to your username
- Verify username case matches exactly

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open source and available under the [MIT License](LICENSE).

## Acknowledgments

- [Supabase](https://supabase.com) for the amazing backend platform
- [TailwindCSS](https://tailwindcss.com) for the beautiful UI framework
- [Plus Jakarta Sans](https://fonts.google.com/specimen/Plus+Jakarta+Sans) font

## Support

If you encounter any issues or have questions, please:
1. Check the Troubleshooting section
2. Open an issue on GitHub
3. Contact the maintainer

---

Made with ❤️ by CinamonBun
