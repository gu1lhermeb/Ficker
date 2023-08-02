"use client";
import { useState } from "react";
import styles from "./login.module.scss";
import Image from "next/image";
import { request } from "@/service/api";
import Link from "next/link";

export default function Login() {
  const [email, setEmail] = useState<string>("");
  const [password, setPassword] = useState<string>("");

  const handleSubmit = async () => {
    try {
      const response = await request({
        method: "POST",
        endpoint: "login",
        data: {
          email: email,
          password: password,
        },
      });
    } catch (error) {
      //TODO: create treatment to errors
      console.log(error);
    }
  };
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Image src="/logo.png" alt="Logo" width={130} height={27} />
      </div>
      <div className={styles.container}>
        <form
          className={styles.form}
          onSubmit={(event) => {
            event.preventDefault();
            handleSubmit();
          }}
        >
          <h3 style={{ textAlign: "center" }}>Entrar</h3>
          <label htmlFor="email" style={{ marginBottom: 5 }}>
            Email
          </label>
          <input
            type="email"
            id="email"
            required
            className={styles.input}
            value={email}
            onChange={(event) => setEmail(event.target.value)}
          />
          <label htmlFor="password" style={{ marginBottom: 5 }}>
            Senha
          </label>
          <input
            type="password"
            id="password"
            required
            className={styles.input}
            value={password}
            onChange={(event) => setPassword(event.target.value)}
          />
          <div style={{ display: "flex", justifyContent: "center" }}>
            <button type="submit" className={styles.button}>
              Entrar
            </button>
          </div>
          <div style={{ textAlign: "center", marginTop: 10 }}>
            <Link href="/recoveryaccount" style={{ textDecoration: "none", fontSize: 14 }}>
              <p>Esqueceu sua senha?</p>
            </Link>
            <Link href="/createaccount" style={{ textDecoration: "none", fontSize: 14 }}>
              <p>Cadastre-se</p>
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
}
