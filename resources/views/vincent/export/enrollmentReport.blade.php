<table>
    <thead>
    <style>
        td{
            text-align: center;
            font-size: 9px;
            font-family: Arial; 
            vertical-align: middle;
            wrap-text: true;
            border-style: double;
        }
    </style>
        <tr>
            <td colspan="8" style='background-color: #ff99cc'>
                TVET Providers Profile
            </td>
            <td colspan="6" style='background-color: #ccffff'>
                Program Profile
            </td>
            <td colspan="17" style='background-color: #ccffcc'>
                Trainee/Learner Profile
            </td>
            <td colspan="6" style='background-color: #e4deea'>
                Training
            </td>
            <td style='background-color: #dbeef4'>
                Assessment and Certification
            </td>
            <td>
            </td>            
            <td colspan="7" style='background-color: #fdeada'>
                Employment Status
            </td>
        </tr>
        <style>
            td{
                background-color: #bfbfbf;
            }
        </style>
        <tr>

            <td rowspan="2" width="1px">
                Region
            </td>
            <td rowspan="2" width="3.03px">
                Province
            </td>
            <td rowspan="2" width="1.84px">
                Congressional District
            </td>
            <td rowspan="2" width="1.60px">
                Municipality/ City
            </td>
            <td rowspan="2" width="6.06px">
                Name of Provider
            </td>
            <td rowspan="2" style="color:#000;" width="29px">
                Complete Address of Provider/Training Venue (for Mobile Training)
            </td>
            <td rowspan="2">
                Type of Provider
            </td>
            <td rowspan="2">
                Classification of Provider
            </td>
            <td rowspan="2">
                Industry Sector of Qualification
            </td>
            <td rowspan="2">
                TVET Program Registration Status
            </td>
            <td rowspan="2">
                Qualification/ Program Title
            </td>
            <td rowspan="2">
                CoPR Number (for WTR/NTR)
            </td>
            <td rowspan="2">
                Training Calendar Code (for TTIs only)
            </td>
            <td rowspan="2">
                Delivery Mode
            </td>
            <td rowspan="2">
                Family/ Last Name
            </td>
            <td rowspan="2">
                First Name
            </td>
            <td rowspan="2">
                Middle Name
            </td>
            <td rowspan="2">
                Contact Number (landline and/ or cellphone
            </td>
            <td rowspan="2">
                E-mail Address/ Facebook Account/ Twitter/ Instagram
            </td>
            <td colspan="5">
                Complete Permanent Mailing Address
            </td>
            <td rowspan="2">
                Sex
            </td>
            <td rowspan="2">
                Date of Birth<br>(mm-dd-yy)
            </td>
            <td rowspan="2">
                Age
            </td>
            <td rowspan="2">
                Civil<br>Status
            </td>
            <td rowspan="2">
                Highest Grade Completed
            </td>
            <td rowspan="2">
                Nationality
            </td>
            <td rowspan="2">
                Classification of Clients
            </td>
            <td rowspan="2">
                Training Status
            </td>
            <td rowspan="2">
                Type of Scholarships
            </td>
            <td rowspan="2">
                Voucher Number (for TWSP, STEP and PESFA)
            </td>
            <td rowspan="2">
                Date Started (mm-dd-yy)
            </td>
            <td rowspan="2">
                Date Finished (mm-dd-yy)
            </td>
            <td rowspan="2">
                Training Results  
            </td>
            <td>
            </td> 
            <td>
            </td>
            <td rowspan="2" style="background-color: #dcd8c2;">
                Employment Status Before the Training
            </td>
            <td colspan="6" style="background-color: #dcd8c2;">
                Employment Status After the Training
            </td>
        </tr>
        <tr>
        <style>
            td{
                background-color: #c0c0c0;
                font-family: Calibri;
                font-size: 10;
            }
        </style>
            <td width="4"></td><td  width="13"></td><td width="7.5"></td><td width="7"></td><td width="24.28"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            <td>
                Street No. and  Street address
            </td>
            <td>
                Barangay
            </td>
            <td>
                Municipality/ City
            </td>
            <td>
                District
            </td>
            <td>
                Province
            </td>
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            <td style="background-color: #eaf0dd;">
                Date Assessed
            </td>
            <td style="background-color: #eaf0dd;">
                Assessment Results 
            </td>
            <td></td>
            <td style="background-color: #dcd8c2;">
                Date Employed (mm-dd-yy)
            </td>
            <td style="background-color: #dcd8c2;">
                Occupation
            </td>
            <td style="background-color: #dcd8c2;">
                Name of Employer
            </td>
            <td style="background-color: #dcd8c2;">
                Complete Address of Employer
            </td>
            <td style="background-color: #dcd8c2;">
                Classification of Worker
            </td>
            <td style="background-color: #dcd8c2;">
                Monthly Income/ Salary
            </td>
        </tr>
        <tr>
            <?php $letter = 'a'; ?>
            @while($letter != 'au')
            <td style="background-color: #ffffcc;">{{$letter}}</td>
            <?php $letter++; ?>
            @endwhile
        </tr>
    </thead>
    <tbody>
    <style>
        td{
            background-color: #ffffff;
            
        }
    </style>
        <?php $students = \App\Status::where('period',88)->where('course','ELECTRO MECHANICAL TECHNICIAN')->get(); ?>
        @foreach($students as $student)
        <tr>
            <td style="border: 1px solid;">
                NCR
            </td>
            <td style="border: 1px solid;">
                PASAY-MAKATI
            </td>
            <td style="border: 1px solid;">
                District I
            </td>
            <td style="border: 1px solid;">
                MAKATI
            </td>
            <td style="border: 1px solid;">
                Don Bosco Technical Institute
            </td>
            <td style="border: 1px solid;">
                Chino Rocess Ave, Brgy. Pio Del Pilar, Makati City
            </td>
            <td style="border: 1px solid;">
                Private
            </td>
            <td style="border: 1px solid;">
                TVIs
            </td>
            
        </tr>
        @endforeach
    </tbody>
    
</table>