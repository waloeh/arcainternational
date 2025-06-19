export function isImage(url) {
  return /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(url);
}

export function isPDF(url) {
  return /\.pdf$/i.test(url);
}

// export function getFileType(url) {
//   if (isPDF(url)) return 'pdf';
//   if (isImage(url)) return 'image';
//   return 'other';
// }