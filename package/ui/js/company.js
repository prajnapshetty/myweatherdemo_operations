require(
    ["aps/load",
    "dojox/mvc/at",
    "aps/xhr",
    "aps/ready!"],
function(load, at, xhr){

    xhr.get("/aps/2/resources/" + aps.context.vars.company.aps.id + "/getTemperature").
    then(function(temperature){

        var widgets = (
            ["aps/PageContainer", [
                ["aps/Output", {
                    content: "Congratulations! A company was created for you in MyWeatherDemo.<br><br>You can now go to <a href='http://www.myweatherdemo.com/login' target='_blank'>http://www.myweatherdemo.com/login</a> to login using username <b>${username}</b> and password <b>${password}</b>.<br><br>To see current weather for your city click on 'Weather Information' tab once logged in.<br><br>For your convenience we also fetched current temperature for ${city}: it's ${celsius}C/${fahrenheit}F",
                    city: at(aps.context.vars.account.addressPostal, "locality"),
                    celsius: at(temperature, "celsius"),
                    fahrenheit: at(temperature, "fahrenheit"),
                    username: at(aps.context.vars.company, "username"),
                    password: at(aps.context.vars.company, "password")
                }]
            ]]);

        load(widgets);
    });
});
