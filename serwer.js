var mysql = require("mysql");

var hostname = "fc8.h.filess.io";
var database = "Nerdshit_highestgo";
var port = "3307";
var username = "Nerdshit_highestgo";
var password = "6abe9d934c104a4a0c39e63a6b2d056dfdcb18a5";

var con = mysql.createConnection({
host: hostname,
user: username,
password,
database,
port,
});

con.connect(function (err) 
{if (err) throw err;
console.log("Connected!");
});

con.query("SELECT * FROM _party").on("result", function (row) {console.log(row);

});