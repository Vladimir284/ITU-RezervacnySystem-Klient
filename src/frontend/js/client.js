// @author Vladimir Meciar (xmecia00)
// @file client.js
// @brief Script for UI for client
// @modified 14.12.2022

// Url for backend
const urlOld = "http://iturezervacnisystem.wz.cz/backend.php"; // Vitos backend
const urlNew = "../backend/client/";
// Global variables, values to be sent to BE in submit to BE
let service;
let employee;
let reservation_date;

// Load all services, ask BE
function loadServices() {
    $("#main_body").html("");
    $("#service_name_text").html("Vyberte si službu");


    $.ajax({
        url: urlOld,
        type: "get",
        data: {
            druhRozhrani: "zakaznik",
            vypissluzeb: "hey"
        },

        // When BE replied succesfully
        success: function (data) {
            jsonData = JSON.parse(parseData(data));
            for (var service in jsonData) {
                $("#main_body").append('<div class="collapsible" id="' + jsonData[service].kategorie + '"><div class="bar_sub_service"><div class="sub_service_red_label"><span class="name">' + jsonData[service].kategorie + '</span></div></div></div>');
                for (var subservices in jsonData[service].podsluzby) {
                    $("#main_body").append('<div class="bar_hidden" height="0" id="' + jsonData[service].podsluzby[subservices] + '">' + jsonData[service].podsluzby[subservices] + '</div>');
                }
            }

            // Collapsible (low budget dropdown menu) opened
            $(".collapsible").on('click', function (e) {
                e.preventDefault();

                $(this).toggleClass("active");
                if (!$(this).hasClass('active')) {
                    $(this).nextUntil('.collapsible').css('height', '0');
                    $(this).nextUntil('.collapsible').css('color', '#F5F5F5');
                } else {
                    $(this).nextUntil('.collapsible').css('color', 'black');
                    $(this).nextUntil('.collapsible').css('height', '50');
                }
            });


            $(".bar_hidden").on('click', function (e) {
                service = $(this).attr('id')
                document.getElementById("reservation_service").innerHTML = service;
                chooseEmployee();
            });
        },

        // Unsuccessful reply from server
        fail: function (e) {
            console.log(e);
        }
    });

}

function parseData(data) {
    const ad = "<!--WZ-REKLAMA-1.0IK-->";
    return data.substring(data.indexOf(ad) + ad.length);
}

// Load choose employee site
function chooseEmployee() {
    $("#main_body").html("");
    $("#service_name_text").html('Vyberte si zamestnanca');

    $.ajax({
        url: urlOld,
        type: "get",
        data: {
            druhRozhrani: "zakaznik",
            dostupnipracovnici: "hey",
            sluzba: service
        },
        success: function (data) {
            jsonData = JSON.parse(parseData(data));

            // Create bars with name of workers
            for (var index in jsonData) {
                $("#main_body").append('<div class="bar" height="100" id="' + jsonData[index] + '">' + jsonData[index] + '</div>');
            }

            $(".bar").on('click', function (e) {
                employee = $(this).attr('id')
                document.getElementById("reservation_employee").innerHTML = employee;
                chooseDate();
            });
        },
        fail: function (e) {
            console.log(e);
        }
    });
}

// Choose date site
function chooseDate() {
    $("#main_body").html("");
    $("#service_name_text").html("Vyberte si datum");

    // New week starts with monday, so I need to load monday from BE
    let prevMonday = new Date();
    prevMonday.setDate(prevMonday.getDate() - (prevMonday.getDay() + 6) % 7);
    updateDates(prevMonday);
}


// Update dates depending on sleected date
function updateDates(monday) {
    var first_day_of_week = formatDate(monday);

    $("#main_body").html(`
    <div>
        <button id="previous"><-</button>
        <button id="next">-></button>
    </div>
    `);

    // Load week after today
    $("#previous").on('click', function (e) {
        monday.setDate(monday.getDate() - 7);
        updateDates(monday, service, employee);
    });

    // Next week
    $("#next").on('click', function (e) {
        monday.setDate(monday.getDate() + 7);
        updateDates(monday, service, employee);
    });

    $.ajax({
        url: urlOld,
        type: "get",
        data: {
            druhRozhrani: "zakaznik",
            seznamvolnychmist: "nefunguje",
            sluzba: service,
            datumprvnihodnetydne: first_day_of_week
        },
        success: function (data) {
            jsonData = JSON.parse(parseData(data));
            let jsonDataDate;
            let firstDayOfWeek;


            // Print bars with dates
            for (let index in jsonData) {
                jsonDataDate = new Date(jsonData[index].den);
                firstDayOfWeek = new Date(first_day_of_week);

                if (dateLiesInWeek(jsonDataDate, firstDayOfWeek)) {
                    for (let timeIdx in jsonData[index].volno) {
                        $("#main_body").append('<div class="bar" height="100" id="' + prettyPrint(jsonData[index], timeIdx) + '">' + prettyPrint(jsonData[index], timeIdx) + '</div>');
                    }
                }
            }

            $(".bar").on('click', function (e) {
                reservation_date = $(this).attr('id');
                document.getElementById("reservation_date").innerHTML = reservation_date;
                userInfo();
            });
        },
        fail: function (e) {

        }
    });
}

/**
 * Checks if date lies in week
 * @param date1
 * @param firstDayOfWeek
 * @returns true if date lies in week{boolean}
 */
function dateLiesInWeek(date1, firstDayOfWeek) {

    if (date1.getFullYear() >= firstDayOfWeek.getFullYear())
        if (date1.getMonth() >= firstDayOfWeek.getMonth())
            if (date1.getDate() >= firstDayOfWeek.getDate())
                if (date1.getFullYear() <= firstDayOfWeek.getFullYear())
                    if (date1.getMonth() <= firstDayOfWeek.getMonth())
                        if (date1.getDate() <= (firstDayOfWeek.getDate()+5))
                            return true;


    return false;

}

// Format print into index.html
function prettyPrint(json, index) {
    return json.zkratkaDne + " (" + json.den + ") o " + json.volno[index];
}

// Edit for format of date
function formatDate(date) {
    return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
}

// User information form, last step
function userInfo() {
    $("#main_body").html(`<div class="form_holder">
    <div class="row">

        <form id="personal_info">
            <div class="column">
                <label for="fname">Krstné meno</label><br>
                <input type="text" id="fname" name="fname"><br><br>
                <label for="lname">Priezvisko:</label><br>
                <input type="text" id="lname" name="lname"><br><br>
            </div>
            <div class="column">
                <label for="lname">Telefónne číslo</label><br>
                <input type="text" id="telnumber" name="telnumber"><br><br>
                <label for="lname">Email</label><br>
                <input type="text" id="email" name="email"><br><br>
            </div>
            <div class="row">
                <label for="lname">Komentár</label><br>
                <textarea id="comment" name="comment"  style="resize: none;"> </textarea><br><br>
                <!-- <input type="text" id="comment" name="comment"><br><br> -->
                <input type="submit" value="Odoslať">
            </div>
        </form>

    </div>
</div>
`);
    $("#service_name_text").html("Vyplňte svoje údaje");


    $("#personal_info").submit(function (e) {
        e.preventDefault();

        // Information collected from form
        let patient = document.getElementById("fname").value + document.getElementById("lname").value;
        let phone = document.getElementById("telnumber").value;
        let email = document.getElementById("email").value;
        let comment = document.getElementById("comment").value;
        let date = reservation_date.substring(reservation_date.indexOf('('), reservation_date.indexOf(')'));
        let time = reservation_date.substring(reservation_date.indexOf('o ') + 1);
        if (patient.length === 0 || phone.length === 0 || email.length === 0 || comment.length === 0) {
            alert("Nevyplnené informácie");
            return;
        }

        // For new backend
        // person = new Object();
        // person.name = patient;
        // person.email = email;
        // person.phone = phone;
        // person.date = date;
        // person.time = time;
        // person.service = service;
        // person.employee = employee;
        $.ajax({
            url: urlNew + "backend.php",
            type: "POST",
            data: {
                name: patient,
                email: email,
                phone: phone,
                date: date,
                time: time,
                service: service,
                employee: employee,
            },
            dataType: 'json',
            async: false,


            // Go back to home page after summit
            success: function (data) {
                alert("Objednávka bola prijatá.\n Bližšie informácie obdržíte v e-mailovej schránke.")
                console.log("done");
                clearTable();
                // loadMainPage();
                // Go to new page
            },

            // Go back to home page after submit
            error: function (e) {
                alert("Nefunkčný backend.\n Objednávka nebyla přijatá.");
                clearTable();
                console.log(e);
                loadMainPage();
            }
        });
    });
}

// Load main page and its attributes
function loadMainPage() {
    loadServices();
}

// Added because of redundance of code
function clearTable(){
    service = "";
    reservation_date = "";
    employee = "";
    document.getElementById("reservation_service").innerHTML = "";
    document.getElementById("reservation_employee").innerHTML = "";
    document.getElementById("reservation_date").innerHTML = "";
}


// (C) Vit Hrbacek
// Following code was made by Vit Hrbacek at xhrbac10@vutbr.cz
function prihlaseniZamestnance() {
    // Načte uživatelské jméno a heslo. Při správných údajích přesměruje uživatele na správnou adresu
    let uzivatelskeJmeno = prompt("Zadejte uživatelské jméno: ");
    switch (uzivatelskeJmeno) {
        case "admin":
        case "Admin": // Vice mozností pro vetsi tolenci k jedné abstrakci účtu
            let heslo = prompt("Zadejte heslo pro uživatele Admin: ");
            switch (heslo) {
                case "admin":
                case "heslo":
                    location.href = '../frontend/adm_wkr/';
                    break;
                default:
                    alert("Zadali jste špatné heslo.");
                    break;
            }
            break;
        case "Jan Novák":
        case "jan novák":
            let jineHeslo = prompt("Zadejte heslo pro uživatele Jan Novák: ");
            switch (jineHeslo) {
                case "heslo":
                    location.href = 'http://iturezervacnisystem.wz.cz/fyzioterapeut.php?user=JanNovák';
                    break;
                default:
                    alert("Zadali jste špatné heslo.");
                    break;
            }
            break;
        default:
            alert("Nezadali jste správné uživatelské jméno.");
    }
}


$(document).ready(function () {

    loadMainPage();

    // If undo image/button is pressed
    $("#undo_home").on('click', function (e) {
        loadMainPage();
        clearTable();
    })

});