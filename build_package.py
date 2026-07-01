import pathlib
import zipfile
import xml.etree.ElementTree as ET

root = pathlib.Path(__file__).resolve().parent
package_dir = root / 'dist' / 'package'
release_dir = root / 'dist' / 'releases'
package_manifest = package_dir / 'pkg_fileuploader.xml'

if not package_dir.exists():
    raise SystemExit(f'Missing package source directory: {package_dir}')

if not package_manifest.exists():
    raise SystemExit(f'Missing package manifest: {package_manifest}')

version = ET.parse(package_manifest).getroot().findtext('version')

if not version:
    raise SystemExit(f'Missing package version in: {package_manifest}')

out_zip = root / 'dist' / f'pkg_fileuploader_{version}.zip'
release_zip = release_dir / f'pkg_fileuploader_{version}.zip'
release_dir.mkdir(parents=True, exist_ok=True)


def write_package(path):
    with zipfile.ZipFile(path, 'w', zipfile.ZIP_DEFLATED) as z:
        for item in sorted(package_dir.rglob('*')):
            if item.is_file():
                z.write(item, item.relative_to(package_dir))


write_package(out_zip)
write_package(release_zip)

print(f'Created {out_zip}')
print(f'Created {release_zip}')
