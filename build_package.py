import pathlib
import zipfile

root = pathlib.Path(__file__).resolve().parent
package_dir = root / 'dist' / 'package'
out_zip = root / 'dist' / 'pkg_fileuploader.zip'

if not package_dir.exists():
    raise SystemExit(f'Missing package source directory: {package_dir}')

with zipfile.ZipFile(out_zip, 'w', zipfile.ZIP_DEFLATED) as z:
    for path in package_dir.rglob('*'):
        if path.is_file():
            z.write(path, path.relative_to(package_dir))

print(f'Created {out_zip}')
