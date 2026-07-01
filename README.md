
This proyect was created for fun

Use Joomla native Media folder work much better than these.


# File Uploader for Joomla 6

This repository contains a Joomla 6-compatible extension for uploading files and folders into a selected directory on the server.

## What it does
- Adds a backend component named File Uploader.
- Lets you choose a target directory and optional subfolder.
- Uploads one or more files into the destination directory.
- Supports folder selection in the browser for a simple upload experience.
- Can be installed in Joomla and updated through an XML update definition.

## Project structure
- dist/package/ contains the installable manifest and component files.
- dist/updates/ contains the Joomla update XML definition.

## Build the extension locally
Run:

```bash
python3 build_package.py
```

This creates installable extension archives for manual installation and update-server publishing.

## Install in Joomla
1. Go to Joomla Administrator.
2. Open System > Install > Extensions.
3. Build the extension locally from the contents of dist/package.
4. Upload the generated extension file.
5. Install it.
6. Open Components > File Uploader to use the component.

## Default upload target
The default target directory is:
- images/fileuploader/uploads

You can change it from the upload form.

## Update server
Joomla can use dist/updates/fileuploader.xml as an update source.

For version 1.0.1, upload these files to the matching public URLs:
- dist/updates/fileuploader.xml -> https://topoweryou.com/updates/fileuploader.xml
- the generated 1.0.1 release archive -> the download URL configured in dist/updates/fileuploader.xml

## Notes
- The component uses the server-side target directory and optional subfolder fields.
- Author metadata is set to Darko Fatur and copyright is set to topoweryou.com in the Joomla manifests.
