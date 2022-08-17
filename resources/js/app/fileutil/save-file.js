import { saveAs } from 'file-saver';

function saveFileAs(response, defaultFileName = 'download') {
    let regex = new RegExp('filename="([^\\\\]+)"');
    let matches = regex.exec(response.headers['content-disposition']);
    let fileName = matches && matches[1] ? matches[1] : defaultFileName;

    let byteArray = new Uint8Array(response.data);
    let blob = new Blob([byteArray], {
        type: 'application/octet-stream',
    });
    saveAs(blob, fileName);
}

export default saveFileAs;
