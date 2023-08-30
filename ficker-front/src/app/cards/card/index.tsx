"use client";
import { CardInformation } from "@/components/CardInformation";
import { TransactionTab } from "@/components/TransactionTab";
import { request } from "@/service/api";
import { Col, Row } from "antd";
import { useEffect, useState } from "react";
import styles from "../../EnterTransaction/entertransaction.module.scss";
import { CardTransactionModal } from "./mcardtransaction";

interface Card {
  best_day: number;
  created_at: Date;
  description: string;
  expiration: number;
  flag_id: number;
  id: number;
  updated_at: Date;
  user_id: number;
}

interface CardProps {
  card: Card;
}

function CardPage({ card }: CardProps) {
  const [isModalOpen, setIsModalOpen] = useState<boolean>(false);
  const getCardData = async () => {
    try {
      const response = await request({
        method: "POST",
        endpoint: `invoice/card`,
      });
      console.log(response);
    } catch (error) {}
  };

  const openModal = () => {
    setIsModalOpen(true);
  };

  useEffect(() => {
    getCardData();
  }, []);

  return (
    <Col xl={24}>
      <CardTransactionModal isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen} />
      <Row>
        <Col xl={14}>
          <TransactionTab
            data={[
              {
                id: 1,
                user_id: 1,
                category_id: 1,
                card_id: 1,
                description: "Teste",
                date: new Date(),
                type: "Teste",
                value: 1,
                installments: 1,
                created_at: new Date(),
                updated_at: new Date(),
              },
            ]}
          />
        </Col>
        <Col xl={6}>
          <Col>
            <CardInformation card={card} />
          </Col>
          <Col xl={22}>
            <Row justify={"end"}>
              <button className={styles.button} onClick={openModal}>
                Nova transação
              </button>
            </Row>
          </Col>
        </Col>
      </Row>
    </Col>
  );
}

export default CardPage;
