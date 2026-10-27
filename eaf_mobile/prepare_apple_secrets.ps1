param(
    [Parameter(Mandatory=$false)]
    [string]$FilePath
)

if (-not $FilePath) {
    Write-Host "==========================================================" -ForegroundColor Cyan
    Write-Host "  Apple Secrets Base64 Encoder for GitHub Actions" -ForegroundColor Yellow
    Write-Host "==========================================================" -ForegroundColor Cyan
    Write-Host "Usage: .\prepare_apple_secrets.ps1 -FilePath <path_to_file>"
    Write-Host "Example: .\prepare_apple_secrets.ps1 -FilePath .\distribution.p12"
    Write-Host "Example: .\prepare_apple_secrets.ps1 -FilePath .\embedded.mobileprovision"
    Write-Host ""
    $FilePath = Read-Host "Enter the path of the file to encode (.p12, .mobileprovision, or .p8)"
}

if (-not (Test-Path $FilePath)) {
    Write-Host "Error: File '$FilePath' not found!" -ForegroundColor Red
    exit 1
}

$bytes = [System.IO.File]::ReadAllBytes((Resolve-Path $FilePath).Path)
$base64 = [Convert]::ToBase64String($bytes)
$outFile = "$FilePath.base64.txt"
$base64 | Out-File -FilePath $outFile -Encoding ascii -NoNewline

Write-Host ""
Write-Host "Successfully converted to Base64!" -ForegroundColor Green
Write-Host "Output saved to: $outFile" -ForegroundColor Cyan
Write-Host ""
Write-Host "You can now copy the text inside '$outFile' and paste it into GitHub Repository Secrets." -ForegroundColor Yellow
