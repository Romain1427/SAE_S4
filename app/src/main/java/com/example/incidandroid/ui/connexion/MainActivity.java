package com.example.incidandroid.ui.connexion;

import static android.view.View.INVISIBLE;
import static android.view.View.VISIBLE;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.example.incidandroid.R;
import com.example.incidandroid.data.HttpsTrustManager;
import com.example.incidandroid.ui.ActivityChooser;
import com.example.incidandroid.utils.Api;

import org.json.JSONException;

public class MainActivity extends AppCompatActivity {

    private EditText loginEdit;
    private EditText mdpEdit;
    private String login;
    private String password;
    private Button btnConnexion;
    public static final String CLE_API = "API";
    public static final String USERNAME = "connexion.login";
    private TextView connexionError;
    private Api api;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        loginEdit = findViewById(R.id.login);
        mdpEdit = findViewById(R.id.mdp);
        connexionError = findViewById(R.id.connexion_error_msg);
        btnConnexion = findViewById(R.id.btnConnexion);

        btnConnexion.setOnClickListener(view -> {
            api = Api.getInstance();
            login = loginEdit.getText().toString();
            password = mdpEdit.getText().toString();
            HttpsTrustManager.allowAllSSL(); // certificat lors d’un accès à une API

            api.login(getApplicationContext(), login, password,
                (jsonObject) -> {
                    try {
                        connectUser(jsonObject.getString("api_key"));
                    } catch (JSONException e) {
                        throw new RuntimeException(e);
                    }
                },
                (error) -> connexionError.setVisibility(VISIBLE));
        });
    }

    private void connectUser(String apiKey) {

        connexionError.setVisibility(INVISIBLE);
        Intent intention =
                new Intent(MainActivity.this, ActivityChooser.class);
        intention.putExtra(CLE_API, apiKey);
        intention.putExtra(USERNAME, login);

        startActivity(intention);

    }
}