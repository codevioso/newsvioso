# Setup Commands for Profile Features

Run these commands to complete the profile setup:

## 1. Run Migration (if not already done)
```bash
php artisan migrate
```

## 2. Create Storage Link for Avatar Uploads
```bash
php artisan storage:link
```

## 3. Build Assets
```bash
npm run build
```

## 4. Start Development Server
```bash
php artisan serve
npm run dev
```

## 5. Test Profile Pages
Visit these URLs to test the profile functionality:
- Profile: `http://localhost:8000/secure/administrator/profile`
- Update Profile: `http://localhost:8000/secure/administrator/profile/update`
- Change Password: `http://localhost:8000/secure/administrator/profile/change/password`

## Notes
- Avatar uploads will be stored in `storage/app/public/avatars/`
- Make sure your `.env` file has proper database and mail configuration
- Test with the default users created by the seeder
