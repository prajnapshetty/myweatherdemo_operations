require([
    "dojox/mvc/at",
    "aps/xhr",
    "aps/load",
    "aps/ready!"
], function(at, xhr, load) {

    // calling custom operation getTemperature() defined in subscription_service
    xhr.get("/aps/2/resources/" + aps.context.vars.company.aps.id + "/getTemperature").
    // storing returned json in 'temperature'
    then(function(temperature){

        var widgets = (
            ["aps/PageContainer", [
                ["aps/Output", {
                    id: "description",
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
