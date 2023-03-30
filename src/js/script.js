// Affiche un popup pour confirmer la suppression d'un enseignant
function confirmDelete(teacherId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer l'enseignant?") === true) {
        window.location.href = window.location.href + '?idTeacher=' + teacherId;
   
    }
}

// function alertModify(){
//     alert("La modification a bien été effectuée")
// }

// // function sortTable(columnName) {
// //     var table, rows, switching, i, x, y, shouldSwitch;
// //     table = document.getElementsByTagName("t_teacher")[2];
// //     switching = true;
// //     /* Make a loop that will continue until
// //     no switching has been done: */
// //     while (switching) {
// //       // start by saying: no switching is done:
// //       switching = false;
// //       rows = table.rows;
// //       /* Loop through all table rows (except the
// //       first, which contains table headers): */
// //       for (i = 1; i < (rows.length - 1); i++) {
// //         // start by saying there should be no switching:
// //         shouldSwitch = false;
// //         /* Get the two elements you want to compare,
// //         one from current row and one from the next: */
// //         x = rows[i].getElementsByTagName("td")[getColumnIndex(columnName)];
// //         y = rows[i + 1].getElementsByTagName("td")[getColumnIndex(columnName)];
// //         /* check if the two rows should switch place,
// //         based on the direction, asc or desc: */
// //         if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
// //           // if so, mark as a switch and break the loop:
// //           shouldSwitch = true;
// //           break;
// //         }
// //       }
// //       if (shouldSwitch) {
// //         /* If a switch has been marked, make the switch
// //         and mark that a switch has been done: */
// //         rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
// //         switching = true;
// //       }
// //     }
// //   }
  
// //   function getColumnIndex(columnName) {
// //     var table = document.getElementsByTagName("t_teacher")[2];
// //     var row = table.getElementsByTagName("tr")[2];
// //     for (var i = 0; i < row.cells.length; i++) {
// //       if (row.cells[i].textContent.toLowerCase() == columnName.toLowerCase()) {
// //         return i;
// //       }
// //     }
// //     return -1;
// //   }