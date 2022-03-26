/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.entities;

/**
 *
 * @author karim
 */
public class Categorie {
    float id;
String theme;
String description;

    public Categorie(float id, String theme, String description) {
        this.id = id;
        this.theme = theme;
        this.description = description;
    }

    public Categorie(String theme, String description) {
        this.theme = theme;
        this.description = description;
    }

    public Categorie() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public String getTheme() {
        return theme;
    }

    public void setTheme(String theme) {
        this.theme = theme;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

}
