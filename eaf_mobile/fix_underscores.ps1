# Fix remaining lint issues across all Dart files:
# 1. separatorBuilder: (_, __) => ...SizedBox  →  (_, _) => ...SizedBox
# 2. itemBuilder: (_, __) => ...               →  (_, _) => ...   (where __ is unused)
# 3. AnimatedBuilder: (_, __) =>               →  (_, _) =>
# Note: Only fix true double-underscore patterns — NOT cases where __ is a param name used elsewhere

$libPath = "C:\Users\Admin\eaf_mobile\lib"
$files = Get-ChildItem -Path $libPath -Recurse -Filter "*.dart"

foreach ($f in $files) {
    $content = Get-Content $f.FullName -Raw
    $changed = $false

    # Fix separatorBuilder double-underscore
    if ($content -match 'separatorBuilder: \(_, __\)') {
        $content = $content -replace 'separatorBuilder: \(_, __\)', 'separatorBuilder: (_, _)'
        $changed = $true
    }

    # Fix AnimatedBuilder double-underscore: builder: (_, __)
    if ($content -match 'builder: \(_, __\) =>') {
        $content = $content -replace 'builder: \(_, __\) =>', 'builder: (_, _) =>'
        $changed = $true
    }

    # Fix itemBuilder double underscore where not used
    if ($content -match 'itemBuilder: \(_, __\)') {
        $content = $content -replace 'itemBuilder: \(_, __\)', 'itemBuilder: (_, _)'
        $changed = $true
    }

    if ($changed) {
        Set-Content -Path $f.FullName -Value $content -NoNewline
        Write-Host "Fixed underscores: $($f.Name)"
    }
}

Write-Host "Done."
