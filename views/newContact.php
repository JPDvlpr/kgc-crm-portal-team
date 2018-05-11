<?php
    //php error reporting
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    $states = array('WA','AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL',
        'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
        'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM',
        'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN',
        'TX', 'UT', 'VT', 'VA', 'WV', 'WI', 'WY');
?>

<head>
    <title>New Contact</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/contact.css">
</head>

<body>
    <div class="container">
        <form id="newContact" name="newContact" action="#" method="post">
            <table id="contact" class="table table-bordered">
                <thead>
                    <th colspan="6"><strong>New Contact</strong></th>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">Contact Name:</td>
                        <td colspan="4"><input id="contactName" type="text" name="name" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Address:</td>
                        <td colspan="4">
                            <input id="contactAddress" type="text" name="address" class="form-control" placeholder="Street Address">
                            <div class="input-group w-100">
                                <input id="contactCity" type="text" name="city" class="form-control my-2" placeholder="City">
                                <select id="states" name="state" class="form-control m-2">
                                    <?php
                                    foreach ($states as $state){
                                        echo '<option value="'.$state.'">'.$state.'</option>';
                                    }
                                    ?>
                                </select>
                                <input id="contactZipCode" type="text" name="city" class="form-control my-2" placeholder="Zip Code">
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Phone:</td>
                        <td colspan="4">
                            <div id="cellPhone" class="input-group w-100">
                                (cell)<input id="contactCellPhone" type="text" name="cellPhone" class="form-control m-2">
                            </div>
                            <div id="otherPhone" class="input-group w-100">
                                (other)<input id="contactPhone" type="text" name="phone" class="form-control m-2">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Email Address:</td>
                        <td colspan="4"><input id="contactEmail" type="email" name="email" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Alternate Contact Name:</td>
                        <td colspan="4"><input id="contactAltName" type="text" name="altName" class="form-control"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Alternate Contact Phone:</td>
                        <td colspan="4"><input id="altPhone" type="text" name="altPhone" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
            <button id="submit" type="submit" class="btn" name="contactSubmit">Submit Contact</button>
        </form>
    </div>
</body>