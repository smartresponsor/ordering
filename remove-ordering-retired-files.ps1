param(
    [Parameter(Mandatory = $true)]
    [string] $RootPath,

    [switch] $Apply
)

$ErrorActionPreference = 'Stop'

$componentRoot = Join-Path $RootPath 'Ordering'
if (-not (Test-Path -LiteralPath $componentRoot)) {
    throw "Ordering component was not found under: $RootPath"
}

$targets = @(
    'migrations',
    'sql',
    'src/Migrations',
    'tools/sql'
)

Write-Host "Root: $RootPath"
Write-Host "Component: $componentRoot"
Write-Host "Mode: $(if ($Apply) { 'APPLY - files will be removed' } else { 'DRY-RUN - nothing will be removed' })"
Write-Host ''

foreach ($relative in $targets) {
    $path = Join-Path $componentRoot $relative
    if (-not (Test-Path -LiteralPath $path)) {
        Write-Host "SKIP missing: Ordering/$relative"
        continue
    }

    $items = Get-ChildItem -LiteralPath $path -Recurse -Force -File
    Write-Host "TARGET Ordering/$relative ($($items.Count) files)"

    foreach ($item in $items) {
        $display = $item.FullName.Substring($componentRoot.Length + 1)
        Write-Host "  remove: $display"
    }

    if ($Apply) {
        Remove-Item -LiteralPath $path -Recurse -Force
        Write-Host "  removed directory: Ordering/$relative"
    }

    Write-Host ''
}

if (-not $Apply) {
    Write-Host 'Dry-run complete. Re-run with -Apply to delete these retired schema-first files.'
} else {
    Write-Host 'Retired Ordering schema-first files removed.'
}
