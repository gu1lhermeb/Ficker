"use client";
import Image from "next/image";
import styles from "./createaccount.module.scss";
import Link from "next/link";
import { useState } from "react";
import { request } from "@/service/api";
import { AxiosError } from "axios";
import { message as msg } from "antd";

const CreateAccountPage = () => {
  const [name, setName] = useState<string>("");
  const [email, setEmail] = useState<string>("");
  const [password, setPassword] = useState<string>("");
  const [confirmPassword, setConfirmPassword] = useState<string>("");
  const [error, setError] = useState<boolean>(false);
  const [message, setMessage] = useState<string[]>([]);

  const handleSubmit = async () => {
    if (password !== confirmPassword) {
      return setError(true);
    }
    try {
      const response = await request({
        method: "POST",
        endpoint: "register",
        data: {
          name: name,
          email: email,
          password: password,
          password_confirmation: confirmPassword,
        },
      });
      localStorage.setItem("token", response!.data.data.token);
      msg.success("Cadastro realizado com sucesso!");
      return (window.location.href = "/");
    } catch (error) {
      if (error instanceof AxiosError) {
        setMessage(Object.values(error.response?.data.errors));
      }
    }
  };

  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      {message.length > 0 ? (
        <div className={styles.errorContainer}>
          {message.map((item) => (
            <p style={{ color: "#ee4848" }}>{item}</p>
          ))}
        </div>
      ) : null}
      <div className={styles.container}>
        <form
          className={styles.form}
          onSubmit={(event) => {
            event.preventDefault();
            handleSubmit();
          }}
        >
          <h2 style={{ textAlign: "center" }}>Cadastrar</h2>
          <label htmlFor="name" style={{ marginBottom: 5 }}>
            Nome
          </label>
          <input
            type="text"
            id="name"
            required
            className={styles.input}
            value={name}
            onChange={(event) => setName(event.target.value)}
          />
          <label htmlFor="email" style={{ marginBottom: 5 }}>
            Email
          </label>
          <input
            type="text"
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
            value={password}
            required
            className={styles.input}
            onChange={(event) => setPassword(event.target.value)}
          />
          <label htmlFor="confirmPassword" style={{ marginBottom: 5 }}>
            Confirmar Senha
          </label>
          <input
            type="password"
            id="confirmPassword"
            required
            value={confirmPassword}
            className={styles.input}
            onChange={(event) => setConfirmPassword(event.target.value)}
          />
          {error ? <p style={{ color: "red" }}>*As senhas precisam ser iguais</p> : null}
          <div
            style={{
              display: "flex",
              justifyContent: "center",
              flexDirection: "column",
              alignItems: "center",
            }}
          >
            <button type="submit" className={styles.button}>
              Cadastrar
            </button>
            <Link href={"/login"} style={{ textDecoration: "none" }}>
              <p style={{ fontSize: 14, marginTop: 20, color: "black" }}>Já possui cadastro?</p>
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
};

export default CreateAccountPage;
