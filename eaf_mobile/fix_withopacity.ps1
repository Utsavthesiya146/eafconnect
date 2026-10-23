$libPath = "C:\Users\Admin\eaf_mobile\lib"
$files = Get-ChildItem -Path $libPath -Recurse -Filter "*.dart"
foreach ($f in $files) {
    $content = Get-Content $f.FullName -Raw
    if ($content -match '\.withOpacity\(') {
        $fixed = [System.Text.RegularExpressions.Regex]::Replace(
            $content,
            '\.withOpacity\(([^)]+)\)',
            '.withValues(alpha: $1)'
        )
        Set-Content -Path $f.FullName -Value $fixed -NoNewline
        Write-Host "Fixed: $($f.Name)"
    }
}
Write-Host "Done."
