<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Devport - Lucky Page</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container" style="margin-top: 2%">
        <h2>Welcome to Lucky Page</h2>

        <div class="input-group mb-3" style="margin-top: 5%">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-default">Unique Link</span>
            </div>
            <input type="text" class="form-control" id="uniqueLink" aria-label="Default" aria-describedby="inputGroup-sizing-default" value="{{$unique_link}}" disabled>
            <input type="hidden" value="{{$token}}" id="token">
            <button class="btn-submit" type="submit" class="form-control" name="generateNewLink" onclick="generate()" style="margin-left: 1%">Generate New Link</button>
            <button class="btn-danger" type="submit" class="form-control" name="deactivateLink" onclick="deactivate()" style="margin-left: 1%">Deactivate The Link</button>

        </div>

        <div class="input-group mb-4" style="margin-top: 2%">
            <button class="btn-success form-controll" style="width: 100%; height: 70px;" onclick="randomize()">I'm feeling lucky</button>
            <span id="luckyNumber" class="text-center" style="display: table; margin: 2% auto; font-size: 20px;"></span>
        </div>

        <div class="input-group mb-4" style="margin-top: 2%">
            <button class="btn-primary form-controll" style="height: 40px;" onclick="getHistory()">History</button>
            <table class="table" id="historyTable" style="margin-top: 3%; display: none">
                <thead>
                    <tr>
                        <th scope="col">Lucky Number</th>
                        <th scope="col">Winning Amount</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>

    function generate() {
        let token = document.getElementById('token').value;
        $.ajax({
            url: '/new_unique_link/' + token,
            type: 'GET',
            success: function(data) {
                document.getElementById('uniqueLink').value = data;
            },
            error: function(e) {
                alert(e);
            }
        });
    }

    function deactivate() {
        let token = document.getElementById('token').value;
        $.ajax({
            url: '/deactivate/' + token,
            type: 'GET',
            success: function(data) {
                if (data.code == 'ok') {
                    alert('The link has been deactivated. System will shut down in 3 seconds');
                    setTimeout(() => {
                        window.location = '/';
                    }, 3000)
                }
            },
            error: function(e) {
                alert(e);
            }
        });
    }

    function randomize() {
        let token = document.getElementById('token').value;
        let randomNumber = Math.floor(Math.random() * 1000) + 1;
        let numberResult = '';

        $.ajax({
            url: '/lucky_page/random/' + token + '/' + randomNumber,
            type: 'GET',
            success: function() {
                if (randomNumber % 2 == 0) {
                    numberResult = ' You won!';
                } else {
                    numberResult = ' You lost :('
                }
                document.getElementById('luckyNumber').innerHTML = randomNumber + numberResult;
            },
            error: function(e) {
                alert(e)
            }
        });
    }

    function getHistory() {
        let token = document.getElementById('token').value;

        $.ajax({
            url: '/lucky_page/history/' + token,
            type: 'GET',
            success: function(data) {
                console.log(data)
                if (data.code == 'ok') {
                    $('#historyTable tr').not(':first').remove();
                    var html = '';
                    for (var i = 0; i < data.data.length; i++)
                        html += '<tr><td>' + data.data[i].lucky_number +
                        '</td><td>' + data.data[i].winning_amount + '</td></tr>';
                    $('#historyTable tr').first().after(html);
                    $('#historyTable').css('display', 'flex');

                } else {
                    console.log(data)
                }
            },
            error: function(e) {
                alert(e)
            }
        });
    }
</script>