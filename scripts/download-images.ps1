Param()

$ErrorActionPreference = "SilentlyContinue"

$root = "https://5thpillartakaful.com"
$siteMapUrl = "$root/sitemap/"

Write-Host "Fetching sitemap from $siteMapUrl..."
$response = Invoke-WebRequest -Uri $siteMapUrl

# Collect all page URLs from sitemap links
$pages = @()
foreach ($link in $response.Links) {
    if (-not $link.href) { continue }
    if ($link.href -like "javascript:*") { continue }

    if ($link.href -like "http*") {
        $url = $link.href
    } else {
        $baseUri = [System.Uri]::new($siteMapUrl)
        $url = [System.Uri]::new($baseUri, $link.href).AbsoluteUri
    }

    if ($url.StartsWith($root)) {
        $pages += $url
    }
}

$pages += $root
$pages = $pages | Sort-Object -Unique

Write-Host "Discovered $($pages.Count) pages. Starting image download..."

$outputDir = "public\assets\images"
if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir | Out-Null
}

foreach ($pageUrl in $pages) {
    Write-Host "Scanning images from $pageUrl"
    try {
        $pageResponse = Invoke-WebRequest -Uri $pageUrl
    } catch {
        Write-Host "Failed to fetch $pageUrl"
        continue
    }

    foreach ($img in $pageResponse.Images) {
        $src = $img.src
        if (-not $src) { continue }

        if ($src -notlike "http*") {
            $baseUri = [System.Uri]::new($pageUrl)
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

        Write-Host "  Downloading $src -> $destPath"
        try {
            Invoke-WebRequest -Uri $src -OutFile $destPath
        } catch {
            Write-Host "  Failed to download $src"
        }
    }
}

Write-Host "Image download complete."

