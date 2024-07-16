/**
 * Account Settings - Account
 */

'use strict';
var kt = 0;
var filesData = [];
document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const deactivateAcc = document.querySelector('#formAccountDeactivation');
    // Update/reset user image of account page
    const fileInput = document.querySelector('.account-file-input'),
      resetFileInput = document.querySelector('.account-image-reset');
    let imgBlock = document.querySelector('#imgblock');
    fileInput.onchange = () => {
      datahandlefun();
    };
    const datahandlefun = () => {
      if (document.getElementById('rem')) {
        document.getElementById('rem').remove();
      }

      for (let i = 0; i < fileInput.files.length; i++) {
        kt++;
        var accountUserImage = document.createElement('img');
        var div_block = document.createElement('div');

        var fileType = fileInput.files[i].type;
        var fileName = fileInput.files[i].name;
        filesData.push(fileInput.files[i]);
        console.log(fileType + '//' + fileName);
        var imageTypes = /^image\//;
        var docTypes =
          /^(application\/msword|application\/vnd\.openxmlformats-officedocument\.wordprocessingml\.document)$/;
        var pdfTypes = /^application\/pdf$/;
        var excelTypes =
          /^(application\/vnd\.ms-excel|application\/vnd\.openxmlformats-officedocument\.spreadsheetml\.sheet)$/;
        var docExtensions = /\.(doc|docx)$/i;
        var excelExtensions = /\.(xls|xlsx)$/i;

        if (fileInput.files[i]) {
          accountUserImage.className = 'd-block rounded';
          accountUserImage.id = 'uploadedAvatar@' + kt;
          accountUserImage.width = 100;
          accountUserImage.height = 100;
          accountUserImage.style.padding = '5px';
          document.querySelector('#fileupload').value =
            document.querySelector('#fileupload').value + ',' + window.URL.createObjectURL(fileInput.files[i]);
          if (imageTypes.test(fileType)) {
            accountUserImage.src = window.URL.createObjectURL(fileInput.files[i]);
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else if (docTypes.test(fileType) || docExtensions.test(fileName)) {
            accountUserImage.src = '/assets/img/avatars/doc.png';
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else if (pdfTypes.test(fileType)) {
            accountUserImage.src = '/assets/img/avatars/pdf.png';
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else if (excelTypes.test(fileType) || excelExtensions.test(fileName)) {
            accountUserImage.src = '/assets/img/avatars/excel.jpg';
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else {
            alert('Unsupported file type.');
          }
        }
        var cross = document.createElement('span');
        cross.className = 'bx bx-message-square-x';
        cross.style.cssText = 'position:absolute;top:0;right:0;color:red;';
        cross.onclick = function (event) {
          event.target.closest('div').remove();
        };

        div_block.style.cssText = 'float:left;position:relative;';
        div_block.id = 'blockid@' + kt;
        div_block.appendChild(accountUserImage);
        div_block.appendChild(cross);
        imgBlock.appendChild(div_block);
      }
      var fileList = new DataTransfer();
      for (var i = 0; i < filesData.length; i++) {
        fileList.items.add(filesData[i]);
      }
      console.log(fileList);
      fileInput.files = fileList.files;
      console.log(fileInput.files);
    };

    // resetFileInput.onclick = () => {
    //   fileInput.value = '';
    //   accountUserImage.src = resetImage;
    // };
    // }
  })();
});
