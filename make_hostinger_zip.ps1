$zipPath = 'd:\xampp\htdocs\eaf\API_UPDATE_FOR_HOSTINGER.zip'
if (Test-Path $zipPath) { Remove-Item $zipPath -Force }

$tempDir = 'd:\xampp\htdocs\eaf\scratch_api_zip'
if (Test-Path $tempDir) { Remove-Item $tempDir -Recurse -Force }
New-Item -ItemType Directory -Path "$tempDir\app\Controllers4\Api" -Force | Out-Null
New-Item -ItemType Directory -Path "$tempDir\app\Filters" -Force | Out-Null
New-Item -ItemType Directory -Path "$tempDir\app\Models" -Force | Out-Null
New-Item -ItemType Directory -Path "$tempDir\app\Config" -Force | Out-Null

Copy-Item -Path 'app\Controllers4\Api\*' -Destination "$tempDir\app\Controllers4\Api" -Force
Copy-Item -Path 'app\Filters\ApiAuth.php' -Destination "$tempDir\app\Filters\ApiAuth.php" -Force
Copy-Item -Path 'app\Models\ApiTokenModel.php' -Destination "$tempDir\app\Models\ApiTokenModel.php" -Force
Copy-Item -Path 'app\Config\Routes.php' -Destination "$tempDir\app\Config\Routes.php" -Force
Copy-Item -Path 'app\Config\Filters.php' -Destination "$tempDir\app\Config\Filters.php" -Force
Copy-Item -Path 'app\Config\Autoload.php' -Destination "$tempDir\app\Config\Autoload.php" -Force

Compress-Archive -Path "$tempDir\app" -DestinationPath $zipPath -Force
Remove-Item $tempDir -Recurse -Force

Get-Item $zipPath | Select-Object Name, Length
Write-Host "ZIP created successfully at: $zipPath"
