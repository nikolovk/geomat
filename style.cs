/* 
    Document   : style
    Created on : 2013-1-27, 10:00:03
    Author     : krasi
    Description:
        Purpose of the stylesheet follows.
*/

body, ul, li, div, p{
    margin:0;
    padding:0;
}

.error {
    color:red;
}
body>header,body>section{
    width:960px;
    margin: 0 auto;
}

nav li{
    list-style-type: none;
    float:left;
}
nav {
    margin: 20px 0;
}

nav a{
    display: block;
    padding: 5px 10px;
    text-decoration: none;
    color: black;
    font-size: 16px;
    /*    border-right: 1px solid black;*/
}
nav a:hover{
    text-decoration: underline;
}

.clearfix {
    zoom: 1;
}

.clearfix:after {
    content: "";
    display: block;
    clear: both;
    height: 0;
}

form {
    margin: 20px 0;
}

table, td, th {
    border: 1px solid rgb(175, 167, 167);
    border-collapse: collapse;
}
td{
    padding: 3px 7px;
}

form label, form input, form select{
    display: block;
    float: left;
}
form label{
    width: 80px;
    margin-right: 10px;
}
form p{
    margin: 5px 0;
}
