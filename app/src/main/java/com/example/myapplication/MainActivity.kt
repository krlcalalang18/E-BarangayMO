package com.example.myapplication

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import com.example.myapplication.ui.theme.MyApplicationTheme
import androidx.appcompat.app.AppCompatActivity

import android.content.Intent

class MainActivity : AppCompatActivity() {
    private lateinit var btnLogin: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        val etUsername = findViewById<EditText>(R.id.etUsername)
        val etPassword = findViewById<EditText>(R.id.etPassword)
        btnLogin = findViewById(R.id.btnLogin)

        btnLogin.setOnClickListener {
            val username = etUsername.text.toString()
            val password = etPassword.text.toString()

            // TODO: Implement your login logic here
        }

        val btnForgotPassword: Button = findViewById(R.id.btnForgotPassword)
        val btnRegister: Button = findViewById(R.id.btnRegister)
        val btnEmergencyDial: Button = findViewById(R.id.btnEmergencyDial)

        btnForgotPassword.setOnClickListener {
            // TODO: Implement "Forgot Password" logic here
        }

        btnRegister.setOnClickListener {
            // TODO: Implement "Register" logic here
        }

        btnEmergencyDial.setOnClickListener {
            // TODO: Implement "Emergency Dial" logic here
        }
    }
}
