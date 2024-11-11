function form_values_to_array(form_name, input_name) {
    let values = [];
    const targets = document.querySelectorAll('form[name="'+form_name+'"] input[name="'+input_name+'"]:checked');

    targets.forEach(target => {
        values.push(target.value);
    });

    return values;
};



function POST_formdata(form_name, input_names, destination_file, destination) {
    let values = {};
    input_names.forEach(name => {
        values[name] = form_values_to_array(form_name, name)
    });
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log('Data sent successfully');
            document.getElementById(destination).innerHTML = this.responseText
        }
    };
    xhttp.open("POST", destination_file, true);
    xhttp.setRequestHeader('Content-Type', 'application/json');
    xhttp.send(JSON.stringify(values));
};



function create_event_listeners(path, event, destination_file, destination, form_name, input_names) {
    document.querySelectorAll(path).forEach(input => {
        input.addEventListener(event, function() {
            POST_formdata(form_name, input_names, destination_file, destination)
            if (document.querySelector('#selected_games').innerHTML!='') {
                document.querySelector('#all_games').innerHTML=''
            }
        });
    });
};



document.addEventListener("DOMContentLoaded",function() {
    const form = document.querySelector('form[name="gamefinder"]');
    const inputs = form.querySelectorAll('input[type="checkbox"]:checked');
    if (inputs.length === 0) {
        POST_formdata('gamefinder', ['tags', 'platforms', 'developers', 'publishers'], 'index.html.php', 'selected_games');
    }
    create_event_listeners('form[name="gamefinder"] fieldset input', 'change', 'index.html.php', 'selected_games', 'gamefinder', ['tags', 'platforms', 'developers', 'publishers'])
});