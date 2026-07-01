# File Uploader for Joomla 6

This repository contains a Joomla 6-compatible extension package for uploading files and folders into a selected directory on the server.

## What it does
- Adds a backend component named File Uploader.
- Lets you choose a target directory and optional subfolder.
- Uploads one or more files into the destination directory.
- Supports folder selection in the browser for a simple upload experience.
- Can be installed as a Joomla package and updated through an XML update definition.

## Project structure
- dist/package/ contains the installable package manifest and component files.
- dist/updates/ contains the Joomla update XML definition.
- The package ZIP is generated locally when needed and is not stored in the repository.

## Build the package ZIP locally
Run:

```bash
python3 build_package.py
```

This creates dist/pkg_fileuploader.zip locally. The archive is ignored by Git and will not be pushed to GitHub.

## Install in Joomla
1. Go to Joomla Administrator.
2. Open System > Install > Extensions.
3. Build the package ZIP locally from the contents of dist/package.
4. Upload the generated package file.
5. Install it.
6. Open Components > File Uploader to use the component.

## Default upload target
The default target directory is:
- images/fileuploader/uploads

You can change it from the upload form.

## Update server
Joomla can use dist/updates/fileuploader.xml as an update source once your real update URL is configured.

## Notes
- The component uses the server-side target directory and optional subfolder fields.
- Author metadata is set to Darko Fatur and copyright is set to topoweryou.com in the Joomla package manifests.
