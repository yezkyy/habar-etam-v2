# Habar Etam - Direct Production Deploy Script
$ErrorActionPreference = "Stop"

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  HABAR ETAM - DIRECT PRODUCTION DEPLOY  " -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

# 1. Build frontend assets locally
Write-Host "`n[1/4] Compiling frontend assets (npm run build)..." -ForegroundColor Yellow
npm run build

# 2. Archive application source code & build assets (excluding vendor, node_modules, .env, storage)
Write-Host "`n[2/4] Packaging application code & assets into deploy bundle..." -ForegroundColor Yellow
if (Test-Path "deploy_bundle.tar.gz") { Remove-Item "deploy_bundle.tar.gz" -Force }

tar -czf deploy_bundle.tar.gz `
    --exclude="node_modules" `
    --exclude="vendor" `
    --exclude=".git" `
    --exclude=".env" `
    --exclude="storage" `
    --exclude="deploy_bundle.tar.gz" `
    app bootstrap config database public resources routes composer.json package.json

# 3. Upload package to cPanel server
Write-Host "`n[3/4] Uploading bundle to cPanel server (195.88.211.130)..." -ForegroundColor Yellow
scp -P 22 deploy_bundle.tar.gz habareta@195.88.211.130:/home/habareta/habar-etam-v2/

# 4. Extract bundle, sync public_html assets, and optimize Laravel
Write-Host "`n[4/4] Extracting code & refreshing cache on server..." -ForegroundColor Yellow
ssh -p 22 habareta@195.88.211.130 "cd /home/habareta/habar-etam-v2 && tar -xzf deploy_bundle.tar.gz && rm -f deploy_bundle.tar.gz && cp -ru /home/habareta/habar-etam-v2/public/* /home/habareta/public_html/ 2>/dev/null || true && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache"

# Clean up local archive
if (Test-Path "deploy_bundle.tar.gz") { Remove-Item "deploy_bundle.tar.gz" -Force }

Write-Host "`n Deployment to production completed successfully! All code & views updated." -ForegroundColor Green
