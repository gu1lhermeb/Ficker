"use client";
import Link from "next/link";
import Image from "next/image";
import styles from "./entertransaction.module.scss";

const EnterTransaction = () => {
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <div style={{ display: "flex", flexDirection: "row" }}>
        <div style={{ background: "#fff", height: "90vh", minWidth: "15vw", paddingTop: 70 }}>
          <div style={{ display: "flex" }}></div>
        </div>
        <div style={{ paddingTop: 10, width: "85%" }}>
          <div style={{ padding: 20 }}>
            <div style={{ display: "flex", justifyContent: "space-between" }}>
              <h3>Entradas</h3>
              <div>
                <input className={styles.input} placeholder="Procurar..." />
                <button className={styles.button}>Nova Transação</button>
              </div>
            </div>
          </div>
          <div>
            <table className={styles.table}>
              <thead>
                <tr>
                  <th style={{ width: 100 }}>Editar</th>
                  <th>Descrição</th>
                  <th style={{ width: 200 }}>Data</th>
                  <th style={{ width: 150 }}>Valor</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style={{ display: "flex", justifyContent: "center", alignItems: "center" }}>
                    <button style={{ background: "none", border: "none" }} onClick={() => {}}>
                      <Image src="/edit.png" alt="Editar" width={20} height={20} />
                    </button>
                  </td>
                  <td>Curso de Java</td>
                  <td>13/04/2023</td>
                  <td className={styles.income}>R$12.000</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
};

export default EnterTransaction;
