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
- dist/pkg_fileuploader.zip is the packaged extension ready to upload in Joomla.

## Install in Joomla
1. Go to Joomla Administrator.
2. Open System > Install > Extensions.
3. Upload the package file dist/pkg_fileuploader.zip.
4. Install it.
5. Open Components > File Uploader to use the component.

## Default upload target
The default target directory is:
- images/fileuploader/uploads

You can change it from the upload form.

## Update server
Joomla can use dist/updates/fileuploader.xml as an update source once your real update URL is configured.

## Notes
- The component uses the server-side target directory and optional subfolder fields.
- Replace the example author and update URLs in the package manifest before distributing it publicly.
