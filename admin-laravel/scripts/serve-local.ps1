param(
    [string] $HostAddress = '127.0.0.1',
    [int] $Port = 8005
)

$projectRoot = Split-Path -Parent $PSScriptRoot
$uploadTmp = Join-Path $projectRoot 'storage\framework\php-upload'
$iniScanDir = Join-Path $projectRoot '.php-ini'

New-Item -ItemType Directory -Force -Path $uploadTmp | Out-Null

$env:LOCAL_PHP_UPLOAD_TMP = $uploadTmp
$env:PHP_INI_SCAN_DIR = $iniScanDir

Set-Location -LiteralPath $projectRoot
php artisan serve --host=$HostAddress --port=$Port --no-reload

