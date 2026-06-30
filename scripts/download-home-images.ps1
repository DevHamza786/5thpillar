Param()

$ErrorActionPreference = "SilentlyContinue"

$root = "https://5thpillartakaful.com"
$homeUrl = "$root/"

Write-Host "Fetching home page from $homeUrl..."

try {
    $response = Invoke-WebRequest -Uri $homeUrl
} catch {
    Write-Host "Failed to fetch home page."
    exit 1
}

$outputDir = "public\assets\images"
if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir | Out-Null
}

foreach ($img in $response.Images) {
    $src = $img.src
    if (-not $src) { continue }

    if ($src -notlike "http*") {
        $baseUri = [System.Uri]::new($homeUrl)
        $src = [System.Uri]::new($baseUri, $src).AbsoluteUri
    }

    try {
        $uri = [System.Uri]::new($src)
    } catch {
        continue
    }

    $name = [System.IO.Path]::GetFileName($uri.AbsolutePath)
    if (-not $name) { continue }

    $destPath = Join-Path $outputDir $name
    if (Test-Path $destPath) {
        continue
    }

    Write-Host "Downloading $src -> $destPath"
    try {
        Invoke-WebRequest -Uri $src -OutFile $destPath
    } catch {
        Write-Host "Failed to download $src"
    }
}

Write-Host "Home page image download complete."

