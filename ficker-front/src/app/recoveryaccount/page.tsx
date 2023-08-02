import Image from "next/image";
import styles from "./recoveryaccount.module.scss";

const RecoveryAccount = () => {
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Image src="/logo.png" alt="Logo" width={130} height={27} />
      </div>
      <div className={styles.container}>
        <div className={styles.content}>
          <h3 style={{ textAlign: "center", marginBottom: 50 }}>Esqueceu sua senha?</h3>
          <label className={styles.label} htmlFor="email">
            E-mail
          </label>
          <input className={styles.input} id="email" type="email" />
          <div style={{ display: "flex", justifyContent: "center" }}>
            <button className={styles.button}>Enviar</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default RecoveryAccount;
