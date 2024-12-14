<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::select('select * from mailbox', [1]);
        dd($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    function generatePasswordHash(string $scheme, string $password)
    {
        // Map 'BCRYPT' to 'BLF-CRYPT'
        if (strtoupper($scheme) === 'BCRYPT') {
            $scheme = 'BLF-CRYPT';
        }

        // Use doveadm to hash the password (requires dovecot installed and accessible)
        $command = sprintf('doveadm pw -s %s -p %s', escapeshellarg($scheme), escapeshellarg($password));

        $output = [];
        $returnVar = null;

        // Execute shell command
        exec($command, $output, $returnVar);

        // Check for errors
        if ($returnVar !== 0 || empty($output)) {
            return false;
        }

        // Return the hashed password
        return trim(implode("\n", $output));
    }

    public function store(Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(['error' => 'Requests must be in JSON format.'], 400);
        }
        $token = $request->token;
        if (!$token == env('APP_KEY')) {
            return response()->json(['error' => 'Requests api Invalid.'], 400);
        }
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum 8 karakter
                'regex:/[A-Z]/', // Harus ada minimal 1 huruf kapital
                'regex:/[a-z]/', // Harus ada minimal 1 huruf kecil
                'regex:/[0-9]/', // Harus ada minimal 1 angka
                'regex:/[@$!%*?&.]/', // Harus ada minimal 1 simbol khusus
                'confirmed', // Harus ada password_confirmation yang sama
            ]
        ], [
            // Pesan error kustom
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol(@$!%*?&.).',
            'password.min' => 'Password harus minimal 8 karakter.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $username = $request->username;
        $domain = 'rsudps.com';
        $password = $request->password;
        $DATE = date('Y.m.d.H.i.s');
        $STORAGE_BASE_DIRECTORY = "/var/vmail/vmail1";
        $STORAGE_BASE = "/var/vmail/";
        $STORAGE_NODE = "vmail1";
        $PASSWORD_SCHEME = 'SSHA512';
        $DEFAULT_QUOTA = '1024';
        $MAILDIR_STYLE = 'hashed';      # hashed, normal.

        if ($MAILDIR_STYLE === "hashed") {
            // Calculate string lengths and substrings
            $length = strlen($username);
            $str1 = substr($username, 0, 1);
            $str2 = $length > 1 ? substr($username, 1, 1) : $str1;
            $str3 = $length > 2 ? substr($username, 2, 1) : $str2;

            // Use mbox, will be changed later
            $maildir = sprintf("%s/%s/%s/%s/%s-%s/", $domain, $str1, $str2, $str3, $username, $DATE);
        } else {
            $maildir = sprintf("%s/%s-%s/", $domain, $username, $DATE);
        }

        $hashedPassword = $this->generatePasswordHash('BCRYPT', $password);
        
        if ($hashedPassword) {
            return response()->json($hashedPassword, 200);
        } else {
            return response()->json($hashedPassword, 400);
        }
        $data_create = [
            "username" => $username . '@' . $domain,
            "password" => $hashedPassword,
            "storagebasedirectory" => $username,
            "storagebasedirectory" => $STORAGE_BASE,
            "storagenode" => $STORAGE_NODE,
            "maildir" => $maildir,
            "quota" => $DEFAULT_QUOTA,
            "domain" => $domain,
            "active" => '1',
            "passwordlastchange" => now(),
            "created" => now(),
        ];
        $data_forwarding = [
            "address" => $username . '@' . $domain,
            "forwarding" => $username . '@' . $domain,
            "domain" => $domain,
            "dest_domain" => $domain,
            "is_forwarding" => '1',

        ];

        return response()->json([$data_create, $data_forwarding], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}