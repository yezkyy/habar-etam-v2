# Habar Etam - Direct Production Deploy Script
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  HABAR ETAM - DIRECT PRODUCTION DEPLOY  " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

# 1. Build frontend assets locally
Write-Host "`n[1/3] Compiling frontend assets (npm run build)..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "Build failed! Aborting deployment." -ForegroundColor Red
    exit 1
}

# 2. Upload compiled assets to cPanel public_html/build and public/build
Write-Host "`n[2/3] Uploading build assets & files to cPanel..." -ForegroundColor Yellow
scp -r -p public/build habareta@195.88.211.130:/home/habareta/habar-etam-v2/public/
scp -r -p public/build habareta@195.88.211.130:/home/habareta/public_html/

# 3. Trigger remote Laravel optimizations & migration
Write-Host "`n[3/3] Running Laravel optimization & cache clear on cPanel..." -ForegroundColor Yellow
ssh -p 22 habareta@195.88.211.130 "cd /home/habareta/habar-etam-v2 && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache"

Write-Host "`n Deployment to production completed successfully!" -ForegroundColor Green
