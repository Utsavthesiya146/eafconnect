# ============================================================
# EAF Connect API Deployment Script
# Run this script as Administrator to deploy the mobile API files
# Right-click PowerShell → "Run as Administrator"
# Then run: .\deploy_api.ps1
# ============================================================

$staging = "C:\Users\Admin\eaf_api_files"
$dest    = "F:\eaf"

Write-Host "Deploying EAF Connect Mobile API files..." -ForegroundColor Cyan

# ── Create destination directories if needed ─────────────────
$dirs = @(
    "$dest\app\Controllers4\Api",
    "$dest\app\Models",
    "$dest\app\Filters"
)

foreach ($dir in $dirs) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "Created: $dir" -ForegroundColor Green
    }
}

# ── Copy API Controllers ─────────────────────────────────────
$apiFiles = @(
    "app\Controllers4\Api\Auth.php",
    "app\Controllers4\Api\Dashboard.php",
    "app\Controllers4\Api\Profile.php",
    "app\Controllers4\Api\Connect.php",
    "app\Controllers4\Api\Mitb.php",
    "app\Controllers4\Api\F2f.php",
    "app\Controllers4\Api\Meetings.php",
    "app\Controllers4\Api\Visitors.php",
    "app\Controllers4\Api\Notifications.php",
    "app\Controllers4\Api\Chapter.php",
    "app\Controllers4\Api\Broadcast.php"
)

foreach ($file in $apiFiles) {
    $src = "$staging\$file"
    $dst = "$dest\$file"
    if (Test-Path $src) {
        Copy-Item -Path $src -Destination $dst -Force
        Write-Host "Copied: $file" -ForegroundColor Green
    } else {
        Write-Host "Missing source: $src" -ForegroundColor Red
    }
}

# ── Copy Model ───────────────────────────────────────────────
Copy-Item "$staging\app\Models\ApiTokenModel.php" `
          "$dest\app\Models\ApiTokenModel.php" -Force
Write-Host "Copied: app\Models\ApiTokenModel.php" -ForegroundColor Green

# ── Copy Filter ──────────────────────────────────────────────
Copy-Item "$staging\app\Filters\ApiAuth.php" `
          "$dest\app\Filters\ApiAuth.php" -Force
Write-Host "Copied: app\Filters\ApiAuth.php" -ForegroundColor Green

Write-Host ""
Write-Host "==================================================" -ForegroundColor Yellow
Write-Host "MANUAL STEPS REQUIRED:" -ForegroundColor Yellow
Write-Host "==================================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Run SQL migration on live DB:" -ForegroundColor White
Write-Host "   $staging\migration_user_tokens.sql" -ForegroundColor Cyan
Write-Host ""
Write-Host "2. Add to app\Config\Filters.php \$aliases array:" -ForegroundColor White
Write-Host "   'apiAuth' => \App\Filters\ApiAuth::class," -ForegroundColor Cyan
Write-Host ""
Write-Host "3. Add routes to app\Config\Routes.php:" -ForegroundColor White
Write-Host "   Copy content from: $staging\routes_additions.php" -ForegroundColor Cyan
Write-Host "   Paste at the END of Routes.php" -ForegroundColor White
Write-Host ""
Write-Host "4. Add namespace to app\Config\Autoload.php:" -ForegroundColor White
Write-Host "   'App\Controllers4' => APPPATH . 'Controllers4'" -ForegroundColor Cyan
Write-Host ""
Write-Host "Deployment complete!" -ForegroundColor Green
