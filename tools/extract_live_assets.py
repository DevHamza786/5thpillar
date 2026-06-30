import re
import sys
from urllib.parse import urljoin, urlparse
from urllib.request import Request, urlopen


def fetch(url: str) -> str:
    req = Request(
        url,
        headers={
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123 Safari/537.36"
        },
    )
    with urlopen(req, timeout=60) as resp:
        raw = resp.read()
    return raw.decode("utf-8", "ignore")


def normalize(base: str, href: str) -> str:
    return urljoin(base, href)


def main() -> int:
    base = sys.argv[1] if len(sys.argv) > 1 else "https://5thpillartakaful.com/"
    html = fetch(base)

    css = []
    js = []
    for m in re.finditer(r"<link[^>]+href=['\"]([^'\"]+)['\"][^>]*>", html, re.I):
        href = m.group(1).strip()
        if ".css" in href:
            css.append(normalize(base, href))
    for m in re.finditer(r"<script[^>]+src=['\"]([^'\"]+)['\"][^>]*>", html, re.I):
        src = m.group(1).strip()
        if ".js" in src:
            js.append(normalize(base, src))

    def uniq(seq):
        seen = set()
        out = []
        for s in seq:
            if s not in seen:
                seen.add(s)
                out.append(s)
        return out

    css = sorted(uniq(css))
    js = sorted(uniq(js))

    print("BASE", base)
    print("CSS", len(css))
    for u in css:
        print(u)
    print("JS", len(js))
    for u in js:
        print(u)

    # Also attempt to infer font/icon assets referenced from CSS paths.
    # We don't download here; we just list common font extensions found in HTML.
    fonts = []
    for ext in (".woff2", ".woff", ".ttf", ".otf", ".eot", ".svg"):
        for m in re.finditer(re.escape(ext), html, re.I):
            # not a full URL extraction; just indicate presence
            pass

    return 0


if __name__ == "__main__":
    raise SystemExit(main())

