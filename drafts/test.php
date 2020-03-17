<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>File(s) size</title>
<script>
function updateSize() {
  var nBytes = 0,
      oFiles = document.getElementById("uploadInput").files,
      nFiles = oFiles.length;
  for (var nFileId = 0; nFileId < nFiles; nFileId++) {
    nBytes += oFiles[nFileId].size;
  }
  var sOutput = nBytes + " bytes";
  // partie de code facultative pour l'approximation des multiples
  for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
    sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
  }
  // fin de la partie de code facultative
  document.getElementById("fileNum").innerHTML = nFiles;
  document.getElementById("fileSize").innerHTML = sOutput;
}
</script>
</head>

<body onload="updateSize();">
<form name="uploadForm">
<p><input id="uploadInput" type="file" name="myFiles" onchange="updateSize();" multiple> selected files: <span id="fileNum">0</span>; total size: <span id="fileSize">0</span></p>
<p><input type="submit" value="Send file"></p>
</form>
</body>
</html>