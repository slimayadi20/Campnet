/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.entities;

/**
 *
 * @author karim
 */
public class Reclamation {

    float id;
    String description;
    String email;

    public Reclamation(float id, String description, String email) {
        this.id = id;
        this.description = description;
        this.email = email;
    }

    public Reclamation(String description, String email) {
        this.description = description;
        this.email = email;
    }

    public Reclamation() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

}
